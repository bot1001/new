<?php

namespace backend\controllers;

use app\models\Information;
use app\models\SmsLog;
use app\models\TicketBasic;
use common\models\Sms;
use common\models\Up;
use Yii;
use yii\web\UploadedFile;

class AutoController extends \yii\web\Controller
{
    //自动发送短信
    function actionSend()
    {
        ini_set('memory_limit', '2048M'); //重置程序运行内存
        set_time_limit(0); //重置程序运行时间

        //获取发短信的房屋信息
        $realestate = (new \yii\db\Query())
            ->select('house_info.realestate as id, community_basic.community_id, community_basic.community_name as community, community_basic.phone, community_building.building_name as building, house_info.phone as cellphone, community_realestate.room_number as number, community_realestate.room_name as name')
            ->from('house_info')
            ->join('inner join', 'community_realestate', 'community_realestate.realestate_id = house_info.realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->andwhere(['community_basic.sms' => '1', 'house_info.status' => '1', 'house_info.politics' => '1'])
            ->andWhere(['=', 'length(house_info.phone)', '11'])
            ->orderBy('house_info.realestate desc');

        $success = 0; // 短信发送成功条数
        $fail = 0; //短信发送失败条数

        foreach ($realestate->batch(500)  as $r){
            foreach($r as $reale){
                $amount = (new \yii\db\Query()) //查询总欠费
                    ->select('sum(invoice_amount) as amount')
                    ->from('user_invoice')
                    ->andwhere(['realestate_id' => $reale['id'], 'invoice_status' => '0'])
                    ->one();
                $amount = $amount['amount'];

                if(is_null($amount))
                {
                    $fail ++;
                    continue;
                }
                $now = (new \yii\db\Query()) //查询当月费用
                    ->select('sum(invoice_amount) as amount')
                    ->from('user_invoice')
                    ->andwhere(['realestate_id' => $reale['id'], 'invoice_status' => '0', 'year' => date('Y'), 'month' => date('m')])
                    ->one();

                $now = $now['amount']; //当月费用
                $cellphone = $reale['phone']; //物业中心联系方式
                if(empty($cellphone)){ //判断号码是否为空，如果为空默认综合部行政电话
                    $cellphone = "0772-5314739";
                }
                $old = $amount - $now; //计算往期费用
                $add = $reale['number'].' 单元 '. $reale['name'];
                $address = $reale['community'].' '.$reale['building'].' '.$add;

                $signName = '裕家人'; //发送短信模板名称
                $phone = $reale['cellphone']; //接收手机号码
                $SMS = 'SMS_140620003'; //短信模板编号

                $guest = '裕达集团'; //客户

                $SmsParam = "{name:'$address',now:'$now',old:'$old', 'amount': $amount, phone:'$cellphone', guest:'$guest'}"; //组合短信信息
                $result = Sms::Send($signName, $phone, $SMS, $SmsParam); //调用发送短信类

                if($result == '1'){ //发送计数
                    $success ++;
                }else{
                    $fail ++;
                }
            }
        }
         $sms_log = new SmsLog(); //实例化短信发送记录模型

         $sms_log->sign_name = $signName;
         $sms_log->sms = $SMS;
         $sms_log->type = '1';
         $sms_log->count = $fail+$success;
         $sms_log->success = $success;
         $sms_log->sms_time = time();
         $sms_log->sender = '64';
         $sms_log->property = '月度缴费单';

         $sms_log->save(); //保存

        return true;
    }

    //自动查询用户投诉数据
    function actionTicket()
    {
        $session = $_SESSION['user']; //从session中提交用户信息
        $community = $_SESSION['community']; //从session中提取小区

        $role = $session['0']['Role']; //用户角色
        $name = $_SESSION['user']['0']['name']; //用户名称

        if($role == '收银员')
        {
            $info = new Information(); //统一实例化消息模型
            $ticket = new TicketBasic(); //统一实例化投诉模型

            $t = $ticket::find()->select('ticket_id, ticket_number as number, community_id as community, create_time, remind')
                ->andwhere(['ticket_status'=> '1'])
                ->andwhere(['>', 'ticket_number', '128'])
                ->andwhere(['<', 'remind', 10])
                ->andwhere(['in', 'community_id', $community])
                ->asArray()
                ->all();

            if($t){
                $t_number = array_column($t, 'number'); //提取投诉单号
                $number = implode(',',$t_number); //拼接投诉单号
                $i_count = count($t); //计算未处理投诉、建议数量
                $detail = '您小区新增'.$i_count.'例投诉或建议，请务必安排相关人员及时处理！';

                //检查投诉单号是否存在
                $information = $info::find()
                    ->select('ticket_number, times, remind_time')
                    ->where(['ticket_number' => $number])
                    ->asArray()
                    ->one();

                if($information)
                {
                    $now = time(); //获取当前时间
                    $time = $information['remind_time']; //提醒信息中的最后一次提醒时间
                    $second = $now - $time; //计算时间差

                    if($second >= 1800)
                    {
                        $remind = $information['times'] += 1;

                        $info::updateAll(['remind_time' => date(time()),
                            'times' => $remind,
                            'reading' => 0],
                            'ticket_number = :number',
                            [':number' => $information['ticket_number']]);

                        foreach($t as $ts)
                        {
                            //更新投诉列表中的提醒次数
                            $ticket::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);
                        }
                    }
                }else{
                    foreach($t as $ts)
                    {
                        $remind = $ts['remind']; //提醒次数
                        $remind += 1; //提醒次数自动递加

                        //更新投诉列表中的提醒次数
                        $ticket::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);
                    }

                    //模型赋值
                    $info->community = reset($community);
                    $info->target = $name;
                    $info->detail = $detail;
                    $info->times = $remind;
                    $info->reading = 0;
                    $info->ticket_number = $number;
                    $info->remind_time = date(time());

                    $info->save(); //保存
                }
                return $i_count;
            }
        }
        return false; //默认返回值
    }

    //异步上传图片
    function actionImage()
    {
//        $type_pic = $this->file_upload('1',array('jpg', 'gif', 'png', 'jpeg'),'filetest','myfile');
//        echo $type_pic['img_path'];
    }

}
