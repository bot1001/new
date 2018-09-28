<?php
namespace api\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class SmsController extends Controller
{
    //发送验证码
    function actionSend($time, $phone)
    {
        //判断获取验证码时间
        if(!empty($time)){ //如果短信发送时间不为空
            $time = date(time())-$time;
            if($time < '60'){
                $msg = '2'; //一分钟内获取验证码返回2
                $msg = Json::encode($msg);
                return $msg;
            }
        }

        $name = '裕家人'; //应用名称
        $sms = 'SMS_23890023'; //模板编号

        //随机获取六位数验证码
        $code = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        //短信发送
        $SmsParam = "{code:'$code'}"; //组合短信信息
        $r = \common\models\Sms::Send($name, $phone, $sms, $SmsParam); //调用发送短信类

        if($r == '1') //发送成功返回验证码
        {
            $code = ['code' => $code, 'timeStamp' => date(time())]; //组合返回信息
            $code = Json::encode($code);
            return $code;
        }

        return false;
    }
}
