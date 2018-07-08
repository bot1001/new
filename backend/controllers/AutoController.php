<?php

namespace backend\controllers;

use app\models\CommunityBasic;
use app\models\HouseInfo;
use app\models\UserInvoice;
use common\models\Sms;

class AutoController extends \yii\web\Controller
{
    //自动发送短信
    function actionSend()
    {

        $comm = CommunityBasic::find()
            ->select('community_name, community_id ')
            ->where(['sms' => '1'])
            ->indexBy('community_id')
            ->column();
        echo '<pre />';
        print_r($comm);exit;
        $message = HouseInfo::find()
            ->select('house_info.realestate, house_info.phone, sum(user_invoice.invoice_amount) as amount, user_invoice.community_id as community, user_invoice.building_id as building')
            ->joinWith('invoice')
            ->andwhere(['house_info.status' => '1', 'politics' => '1','user_invoice.invoice_status' => '0'])
            ->groupBy('house_info.phone')
            ->orderBy('house_info.realestate desc')
            ->asArray()
            ->all();

        foreach ($message as $m)
        {
            $realestate = $m['realestate'];
            $amount = $m['amount']; //合计欠费金额
            $invoice = $m['invoice']; //提取费项
            $now = 0; //当月费用总和

            foreach ($invoice as $in){ //遍历并求当月费用
                if($in['year'] == date('Y') && $in['month'] == date('m'))
                {
                    $now += $in['invoice_amount'];
                }
            }

            $old = $amount - $now; //历史欠费

//            $community = ()

            echo '<pre />';
            print_r($realestate);
        /*exit;
            $signName = '裕家人'; //发送短信模板名称
            $phone = '15296500211'; //接收手机号码
            $SMS = 'SMS_139425010'; //短信模板编号

            $community = ' 金座小区 5栋1单元 2002 '; // 房号
            $guest = '裕达集团'; //客户

            $SmsParam = "{name:'$community',now:'$now',old:'$old',guest:'$guest'}"; //组合短信信息

            $result = Sms::Send($signName, $phone, $SMS, $SmsParam); //调用发送短信类

            if($result == '1'){
                echo '短信发送成功';
            }else{
                echo '短信发送失败';
            }*/
        }
    }
}
