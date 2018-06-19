<?php
namespace api\controllers;

use yii\web\Controller;
use common\models\UserAccount;
use yii\helpers\Json;

/**
 * Site controller
 */
class LoginController extends Controller
{

    public function actionIndex()
    {
		$get = $_GET;
		$order = UserAccount::find()->one();
		$order = Json::encode($order);
		
		return $get['open_id'];
    }
}
