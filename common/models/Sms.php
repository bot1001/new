<?php

namespace common\models;

use Yii;

class Sms extends \yii\db\ActiveRecord
{
    //发送短信方法
     static function Send($signName, $phone, $SMS, $SmsParam)
    {
        $config = Yii::$app->params['sms']; //从配置文件中获取AppID和secretID
        //引用发送短信类
//        require_once(dirname(__FILE__).'/../../vendor/ali-sms/top/RequestCheckUtil.php');
        require_once(dirname(__FILE__).'/../../vendor/ali-sms/TopSDK.php');
//        require_once(dirname(__FILE__).'/../../vendor/ali-sms/top/request/AlibabaAliqinFcSmsNumSendRequest.php');

        $c = new \TopClient();
        $RequestCheckUtil = new \RequestCheckUtil();

        $c->appkey = $config['appkey']; //短信应用序号
        $c->secretKey = $config['secret']; //短信应用秘钥

        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($signName);
        $req->setSmsParam( $SmsParam );
        $req->setRecNum($phone);
        $req->setSmsTemplateCode($SMS);
        $resp = $c->execute($req);

        if($resp == false){
            return false;
        }
        $r= $resp->result; //提取发送返回结果
        if(empty($r)){
            $re = (array)$resp;
        }else{
            $re = (array)$r; //转换XML数组为普通数组
        }

        if(isset($re['err_code']) && isset($re['success'])) //判断是否存在错误和是否发送成功
        {
            $success = $re['success'];
            $err = $re['err_code'];
            if($err == '0' && $success == true){
                return true; //无错误且发送成功的返回true
            }else{
                return false;
            }
        }
    }
}
