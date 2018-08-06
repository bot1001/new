<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'User Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-invoice-view">

    <?= DetailView::widget([
        'model' => $model,
	    //'order' => $order,
        'attributes' => [
            'order.order_id',
            ['attribute' => 'order.create_time',
			 'format' =>['date','php:Y-m-d H:i:s']],
	        ['attribute' => 'order.payment_time',
			 'format' =>['date','php:Y-m-d H:i:s']],
             /*'order.order_type',
            ['attribute' => 'order.payment_gateway'],*/
             'order.payment_number',
             'order.order_amount',
        ],
    ]) ?>
    <?php
    $amount = \common\models\Order::find() //查询订单金额
    ->select('order_amount as amount')
    ->where(['order_id' => "$model->order_id"])
    ->asArray()
    ->one();
    ?>
    <p align="center">
        <?= Html::a('<span class="glyphicon glyphicon-print"></span>', ['order/print','order_id' => $model->order_id, 'amount' => $amount['amount']], ['class' => 'btn btn-success'])?>
        <?= Html::a('<span class="glyphicon glyphicon-align-justify"></span>', ['order/index','order_id' => $model->order_id], ['class' => 'btn btn-info']) ?>
</p>
</div>
