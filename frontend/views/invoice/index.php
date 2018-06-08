<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房屋缴费';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">
    
    <p align="right"></p>

    <?php
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn', 'header' => '序号'],

            ['attribute' => 'year',
			 'hAlign' => 'center'
			],
            ['attribute' => 'month',
			 'hAlign' => 'center'
			],
            ['attribute' => 'description',
			 'hAlign' => 'center'
			],
            ['attribute' => 'invoice_amount',
			 'hAlign' => 'center'
			],
            ['attribute' => 'order_id',
			 'value' => function($model){
	            $order = $model->order_id;
            	if($order == ''){
            		return '';
            	}else{
            		return $model->order_id;
            	}
            },
			 'hAlign' => 'center'
			],
	
            ['attribute' => 'invoice_notes',
			'value' => function($model){
             	$notes = $model->invoice_notes;
             	if($notes == ''){
             		return '';
             	}else{
             		return $notes;
             	}
             	
             },],
            ['attribute' => 'payment_time',
			 'value' => function($model){
             	$time = $model->payment_time;
             	if($time == '1970-01-01 00:00:00'){
             		return '';
             	}else{
             		return $time;
             	}
             	
             },
			 'hAlign' => 'center'
			],
            ['attribute' => 'invoice_status',
			'value' => function($model){
            	$data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
            	return $data[$model->invoice_status];
            },
			 'hAlign' => 'center'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '费用列表',
				   'before' => Html::a('预交', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
		'toolbar' => [
		    [ 'content' =>
				Html::a( '<span class="glyphicon glyphicon-credit-card"></span>', 'view', [ 'class' => 'btn btn-success gridviewdelete ' ] )
			],
	    	'{toggleData}'
	    ],
    ]); ?>
</div>
