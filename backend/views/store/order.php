<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '我的订单';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
   $gridview = [
       ['class' => 'kartik\grid\SerialColumn',
           'header' => '序<br />号'],

       ['attribute' => 'order_id'],
       ['attribute' => 'product_name'],
       ['attribute' => 'product_price'],
       ['attribute' => 'address',
           'value' => 'address.address',
           'label' => '地址'],

       ['attribute' => 'phone',
           'value' => 'address.mobile_phone',
           'label' => '手机号码'],

       ['attribute' => 'name',
           'value' => 'address.name',
           'label' => '下单人'],
       ['attribute' => 'order.create_time'],
       ['attribute' => 'order.payment_time'],
       ['attribute' => 'order.order_amount'],
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
