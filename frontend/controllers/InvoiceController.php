<?php

namespace frontend\controllers;

use common\models\UserInvoice;
use yii\web\Controller;

class InvoiceController extends Controller
{
	public function actionIndex($id)
	{
		$data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
		$invoice = UserInvoice::find()
			->select('year, month, description, invoice_amount as amount, invoice_status as status')
			->where(['in', 'realestate_id', 22513])
			->orderBy('invoice_status ASC, year DESC, month DESC')
			->asArray()
			->all();
		
	    return $this->render('index',['invoice' => $invoice, 'data' => $data]);
	}
	
}