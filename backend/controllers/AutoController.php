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
        //查找可发送短信的的小区
        $comm = CommunityBasic::find()
            ->select('community_name, community_id ')
            ->where(['sms' => '1'])
            ->indexBy('community_id')
            ->column();

        //遍历小区提取编号
        foreach($comm as $key => $com)
        {
            $community_id[] = $key;
        }

        //查找楼宇
        $b = CommunityBuilding::find()
                ->select('building_name, building_id')
                ->indexBy('building_id')
                ->where(['in', 'community_id', $community_id])
                ->column();

        $message = HouseInfo::find() //查找发送短信的信息
            ->select('house_info.realestate, house_info.phone, sum(user_invoice.invoice_amount) as amount, user_invoice.community_id as community, user_invoice.building_id as building')
            ->joinWith('invoice')
            ->joinWith('re') //关联房屋
            ->andwhere(['house_info.status' => '1', 'politics' => '1', 'user_invoice.invoice_status' => '0'])
            ->andWhere(['in', 'user_invoice.community_id', $community_id])
            ->groupBy('house_info.realestate')
            ->orderBy('house_info.realestate desc')
//            ->limit(3)
            ->asArray()
            ->all();
        echo '<pre />';
        print_r(count($message));
        exit;

        $success = 0; // 短信发送成功条数
        $fail = 0; //短信发送失败条数

        foreach ($message as $m)
        {
            $realestate = $m['realestate'];
            $amount = $m['amount']; //合计欠费金额
            if($amount == '0'){ //判断合计金额为零则终止当前循环
                $fail ++;
                continue;
            }
            $invoice = $m['invoice']; //提取费项
            $now = 0; //当月费用总和

            foreach ($invoice as $in){ //遍历并求当月费用
                if($in['year'] == date('Y') && $in['month'] == date('m'))
                {
                    $now += $in['invoice_amount'];
                }
            }

            $old = $amount - $now; //历史欠费
            $room = $m['re']; //房屋信息
            $add = $room['room_number'].' 单元 '. $room['room_name'];
            $address = $comm[$m['community']].' '.$b[$m['building']].' '.$add;

            $signName = '裕家人'; //发送短信模板名称
            $phone = '15296500211'; //接收手机号码$m['phone'];//
            $SMS = 'SMS_139425010'; //短信模板编号
            $guest = '裕达集团'; //客户

            $SmsParam = "{name:'$address',now:'$now',old:'$old',guest:'$guest'}"; //组合短信信息
            $result = '';
//            $result = Sms::Send($signName, $phone, $SMS, $SmsParam); //调用发送短信类

            if($result == '1'){
                $success ++;
            }else{
                $fail ++;
            }
        }


//         $sms_log = new SmsLog(); //实例化短信发送记录模型
//
//         $sms_log->sign_name = $signName;
//         $sms_log->sms = $SMS;
//         $sms_log->type = '1';
//         $sms_log->count = $fail+$success;
//         $sms_log->success = $success;
//         $sms_log->sms_time = time();
//         $sms_log->property = '月度缴费单';
//
//         $sms_log->save(); //保存

//        return true;
    }
}
