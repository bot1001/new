<?php

namespace frontend\controllers;

use yii\web\Controller;
use app\models\Order;
use common\models\UserInvoice;

class PayController extends Controller
{
	public function actionPay($order_id, $amount)
	{
		echo $order_id;
	}
}