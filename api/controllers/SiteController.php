<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use common\models\Order;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionIndex()
    {
		$order = Order::find()->asArray()->one();
		
		echo '<pre />';
		print_r($order);
    }
}
