<?php

namespace backend\controllers;

use Yii;

class TestController extends \yii\web\Controller
{	
	public function actionTest()
	{
		$cookies = Yii::$app->user;;
		echo '<pre>';
		print_r($cookies);
	}	
}
