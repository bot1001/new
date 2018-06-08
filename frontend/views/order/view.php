<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model['id'];
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
<br />
    <p>
        <?= Html::a('删除', ['delete', 'id' => $model['id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '此操作将不可恢复，您确定要删除吗？',
            ],
        ]) ?>
    </p>

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

</div>
