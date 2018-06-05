<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use app\models\Order;
use frontend\models\LoginForm;
use common\models\UserInvoice;

class PayController extends Controller
{
	public function actionPay($order_id, $amount)
	{
		echo $order_id;
	}
	
	public $enableCsrfValidation = false;//关闭表单验证
	
	public function actionLogin()
	{
		//模拟登陆
		$model = new LoginForm();
		
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
	}
	
	//用户退出
	public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}