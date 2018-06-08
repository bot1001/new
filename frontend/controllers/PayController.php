<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class PayController extends Controller
{
	public $enableCsrfValidation = false;//关闭表单验证
	
	//检查用户是否登录
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest){
			$this->redirect(['/login/login']);
			return false;
		}
		return true;
	}
	
	
	//支付接口
	public function actionPay()
	{
		//接收传参
		$get = $_GET;
		$method = $get['method'];
		$pay = $get['pay'];
		
		$order_id = $pay['order_id'];
		$description = $pay['description'];
		$amount = $pay['order_amount'];
		$body = '物业缴费';  // 订单描述
		
		if($method == 'alipay')
		{
		    return $this->redirect(['alipay','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}elseif($method == 'wx'){
		    return $this->redirect(['wx','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}else{
			return $this->redirect(['jh','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}
	}
	
	//调用支付宝接口
	public function actionAlipay()
	{
		echo 'text';
	}
	
	//调用微信支付接口
	public function actionWx()
	{
		echo 'text';
	}
	
	//调用微信支付接口
	public function actionJh()
	{
		echo 'text';
	}
	
	//支付宝同步回调
	public function actionReturn()
	{
		
	}
	
	//用户退出
	public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}