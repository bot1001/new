<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Login extends Model
{
	public static function Wx($code, $appid)
	{
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=dedd7bad5b2b3c43a8e23597dfa27698&code=$code&grant_type=authorization_code";
		
		$ch = curl_init();
        // 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);
        
        // 释放curl句柄
        curl_close($ch);
				
		return $output;
	}
	
	//微信获取用户信息
	public static function Info($token, $openid)
	{
		$user_info = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		
		$u = curl_init();
        // 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL,$user_info);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($u,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $user = curl_exec($u);
        
        // 释放curl句柄
        curl_close($u);
				
		return $user;
	}
}