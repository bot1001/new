<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\ bootstrap\ Modal;
use yii\ helpers\ Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = '缴费详情：'.$model['id'];
$this->params['breadcrumbs'][] = ['label' => '缴费记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">支付方式</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>', ['pay', 'id' => $model['id']]
] );
$V_Url = Url::toRoute( ['pay', 'id' => $model['id']] );

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
<div class="order-view">
	<style>
		.glyphicon{
			height: 50px;
			font-size: 50px;
			width: 70px;
			display:inline-block;
		}
	</style>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            ['attribute' => 'address',
			'label' => '地址'],
            ['attribute' => 'order_id',
			'label' => '订单号'],
            ['attribute' => 'create_time',
			 'value' => function($model){
             	return date('Y-m-d H:i:s', $model['create_time']);
             },
			'label' => '下单时间'],

            ['attribute' => 'payment_time',
			 'value' => function($model){
            	if($model['payment_time'] == ''){
            		return '';
            	}else{
            		return date('Y-m-d H:i:s', $model['payment_time']);
            	}
            },
			 
			'label' => '支付时间'],
            ['attribute' => 'payment_gateway',
			 'value' => function($model){
	             if(!empty($model['payment_gateway'])){
					 $date = ['1' => '支付宝', '2' => '微信', '6' => '建行'];
             	     return $date[$model['payment_gateway']];
				 }else{
	                 return '';
				 }
             },
			'label' => '支付方式'],
            ['attribute' => 'payment_number',
			 'value' => function($model){
	             if(!empty($model['payment_number'])){
             	     return $model['payment_gateway'];
				 }else{
					 return '';
				 }
             },
			'label' => '支付流水号'],
            ['attribute' => 'description',
			'label' => '详情'],
            ['attribute' => 'order_amount',
			'label' => '合计金额'],
            ['attribute' => 'status',
			 'value' => function($model){
             	$date = ['1' => '未支付','2' => '已支付','3' => '已取消','4' => '送货中','5' => '已签收'];
             	return $date[$model['status']];
             },
			'label' => '状态'],
        ],
    ]) ?>
    
    <p align="center">
        <?= Html::a('<span class="glyphicon glyphicon-credit-card"></span>', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#view-modal', //modal 名字
						'class' => 'btn btn-success view', //操作名
	                    'title' => '立即支付'
					]) ?>
           
	   <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['id']], [
		    'class' => 'btn btn-danger',
		    'data' => [
		    	'confirm' => '此操作将不可恢复，您确定要删除吗？',
		    ], 
		    'title' => '删除'
	]) ?>
</p>   

</div>
