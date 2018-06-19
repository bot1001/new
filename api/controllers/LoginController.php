<?php
namespace api\controllers;

use yii\web\Controller;
use common\models\Order;
use yii\helpers\Json;

/**
 * Site controller
 */
class LoginController extends Controller
{

    public function actionIndex()
    {
		$get = $_GET;
		$order = Order::find()->one();
		$order = Json::encode($order);
		
		return $order;
    }
}
