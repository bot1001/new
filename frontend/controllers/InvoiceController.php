<?php

namespace frontend\controllers;

use common\models\UserInvoice;
use yii\web\Controller;

class InvoiceController extends Controller
{
	public function actionIndex($id)
	{
		$invoice = UserInvoice::find()->where(['in', 'realestate_id', $id])->asArray()->one();
	    print_r($invoice);
	}
	
}