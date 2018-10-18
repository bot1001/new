<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '我的订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<?php
   $gridview = [
       ['class' => 'kartik\grid\SerialColumn',
           'header' => '序<br />号'],

       ['attribute' => 'order_id'],
       ['attribute' => 'product_name',
           'contentOptions' => ['class' => 'text-left']],

       ['attribute' => 'add',
           'value' => 'address.address',
           'contentOptions' => ['class' => 'text-left'],
           'label' => '地址'],

       ['attribute' => 'phone',
           'value' => 'address.mobile_phone',
           'label' => '手机号码'],

       ['attribute' => 'name',
           'value' => 'address.name',
           'contentOptions' => ['class' => 'text-left'],
           'label' => '下单人'],

       ['attribute' => 'create_time',
           'value' => function($model){
               return date('Y-m-d H:i:s', $model->order->create_time);
           },
           'label' => '下单时间'],

       ['attribute' => 'payment_time',
           'value' => function($model){
               return date('Y-m-d H:i:s', $model->order->payment_time);
           },
           'label' => '支付时间'],

       ['attribute' => 'status',
           'value' => 'order.status',
           'label' => '状态'],

       ['attribute' => 'product_price',
           'contentOptions' => ['class' => 'text-right']],

       ['attribute' => 'product_quantity',
           'contentOptions' => ['class' => 'text-right']],

       ['attribute' => 'amount',
           'value' => 'order.order_amount',
           'contentOptions' => ['class' => 'text-right'],
           'label' => '合计'],

       ['class' => 'kartik\grid\checkBoxColumn'],
       ['class' => 'kartik\grid\ActionColumn',
           'header' => '操<br />作'],
   ];
   echo GridView::widget([
       'dataProvider' => $dataProvider,
       'filterModel' => $search,
       'panel' => ['type' => 'info', 'heading' => '我的订单'],
       'columns' => $gridview
   ]);
?>
