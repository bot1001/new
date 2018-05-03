<?php

namespace frontend\controllers;

use Yii;
use common\models\UserInvoice;
use yii\web\Controller;

class HomeController extends Controller
{
	public function actionIndex()
	{
        return $this->render('/home/index');
	}
}
?>