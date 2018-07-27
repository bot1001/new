<?php

namespace backend\controllers;

use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\HouseInfo;
use app\models\SmsLog;
use common\models\Sms;

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
                $old = $amount - $now; //计算往期费用
                $add = $reale['number'].' 单元 '. $reale['name'];
                $address = $reale['community'].' '.$reale['building'].' '.$add;

                $signName = '裕家人'; //发送短信模板名称
                $phone = '15296500211'; //接收手机号码 $reale['cellphone']
                $SMS = 'SMS_140710004'; //短信模板编号

                $guest = '裕达集团'; //客户

                $SmsParam = "{name:'$address',now:'$now',old:'$old', phone:'$cellphone', guest:'$guest'}"; //组合短信信息
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
}
