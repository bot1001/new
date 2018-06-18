<?php

namespace frontend\controllers;

use yii\web\Controller;

class TestController extends Controller
{
	public function actionIndex()
    {	
		$open_id = '123456';
        $test = "http://api.yuda.com/login/index?open_id=$open_id";

		$u = curl_init();
        // 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL,$test);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($u, CURLOPT_SSL_VERIFYPEER, false);// 这个是主要参数
        curl_setopt($u,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $user = curl_exec($u);
        
        // 释放curl句柄
        curl_close($u);
		
		$u = json_decode($user, true); //将json数据格式转换成标准数组
		// 参数true表示将数据转换成标准数组， false表将数据转换成对象数组
		
		echo '<pre >';
		print_r($u);
    }
}