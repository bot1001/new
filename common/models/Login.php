<?php

namespace common\models;

use yii\base\Model;

class Login extends Model
{
	//获取小程序微信用户access_token
	public static function Wx($appid, $secret, $js_code, $grant_type)
	{
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$js_code&grant_type=$grant_type";

        $ch = curl_init();
        // 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 这个是主要参数
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);

        // 释放curl句柄
        curl_close($ch);

		return $output;
	}
	
	//获取微信用户信息
	public static function Info($token, $openid)
	{
		$user_info = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		
		$u = curl_init();
        // 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL,$user_info);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($u, CURLOPT_SSL_VERIFYPEER, false);// 这个是主要参数
        curl_setopt($u,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $user = curl_exec($u);
        
        // 释放curl句柄
        curl_close($u);
				
		return $user;
	}	
}
