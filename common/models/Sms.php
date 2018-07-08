<?php

namespace common\models;

use Yii;

class Sms extends \yii\db\ActiveRecord
{
     static function Send($signName, $phone, $SMS, $SmsParam)
    {
        $config = Yii::$app->params['sms']; //从配置文件中获取AppID和secretID
        //引用发送短信类
        require_once(dirname(__FILE__).'/../../vendor/ali-sms/top/RequestCheckUtil.php');
        require_once(dirname(__FILE__).'/../../vendor/ali-sms/top/TopClient.php');
        require_once(dirname(__FILE__).'/../../vendor/ali-sms/top/request/AlibabaAliqinFcSmsNumSendRequest.php');

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

        $r= $resp->result; //提取发送返回结果
        $re = (array)$r; //转换XML数组为普通数组

        if($re['err_code'] == '0' && $re['success'] == true)
        {
            return true;
        }else{
            return false;
        }
    }
}
