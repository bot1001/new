<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房屋缴费';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">预交</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$V_Url = Url::toRoute( [ 'create' ] );

$vJs = <<<JS
    $('.view').on('click', function () {
        $.get('{$V_Url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $vJs );

Modal::end();

?>

<div class="invoice-index">
<style>
    th, .table tbody{
        text-align: center;
    }
</style>
    <?php
	$home = $_SESSION['home'];
	
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn', 'header' => '序号'],

             'year',
             'month',

            ['attribute' => 'description',
			 'contentOptions' => ['class' => 'text-left']
			],

            ['attribute' => 'invoice_amount',
                'contentOptions' => ['class' => 'text-right']
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
			],
	
            ['attribute' => 'invoice_notes',
			'value' => function($model){
             	$notes = $model->invoice_notes;
             	if($notes == ''){
             		return '';
             	}else{
             		return $notes;
             	}
             },
                'contentOptions' => ['class' => 'text-left']
                ],
            ['attribute' => 'payment_time',
			 'mergeHeader' => true,
			 'value' => function($model){
             	$time = strtotime($model->payment_time);
             	if($time == '0'){
             		return '';
             	}else{
             		return date('Y-m-d H:i:s', $time);
             	}
             },
			 'width' => '150px'
			],

            ['attribute' => 'invoice_status',
			'value' => function($model){
            	$data = [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ];
            	return $data[$model->invoice_status];
            },
			 'filterType'=> GridView::FILTER_SELECT2,
		     'filter'=> [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ],
		     'filterInputOptions'=>['placeholder'=>'请选择'],
			 'filterWidgetOptions'=>[
                             'pluginOptions'=>['allowClear'=>true],
		                 ],
			 ],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => $home['community'].' '.$home['building'].' '.$home['number'].'单元 '.$home['room'] ,
				   'before' => Html::a( '预交',
								'#', [ 
		                'data-toggle' => 'modal',
						'data-target' => '#view-modal',
						'class' => 'btn btn-primary view' ] )],
        'columns' => $gridview,
		'toolbar' => [
		    [ 'content' =>
				Html::a( '<span class="glyphicon glyphicon-credit-card"></span>', 'view', [ 'class' => 'btn btn-success', 'title' => '立即缴费'] ),
			],
	    	'{toggleData}'
	    ],
    ]); ?>
</div>
