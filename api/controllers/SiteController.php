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
		$get = $_GET;

		$order = Order::find()->one();
		return $get['open_id'];
    }
}
