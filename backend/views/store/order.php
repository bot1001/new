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

       ['attribute' => 'address',
           'contentOptions' => ['class' => 'text-left'],
           'label' => '地址'],

       ['attribute' => 'mobile_phone',
           'label' => '手机号码'],

       ['attribute' => 'name',
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
           'filterType' => GridView::FILTER_SELECT2,
           'filter' => $order['status'],
           'filterWidgetOptions' => [
               'pluginOptions' => [ 'allowClear' => true ],
           ],
           'filterInputOptions' => [ 'placeholder' => '请选择' ],
           'value' => function ( $model ) {
               $e = Yii::$app->params['order']['status']; //订单状态数据
               $s = $model['order'][ 'status' ]; //订单状态

               if ( !$s ) {
                   return '';
               } else {
                   return $e[ $s ];
               };
           },
           'label' => '状态'],

       ['attribute' => 'way',
           'filterType' => GridView::FILTER_SELECT2,
           'filter' => $order['way'],
           'filterWidgetOptions' => [
               'pluginOptions' => [ 'allowClear' => true ],
           ],
           'filterInputOptions' => [ 'placeholder' => '请选择' ],
           'value' => function ( $model ) {
               $e = Yii::$app->params['order']['way']; //订单状态数据
               $s = $model['order'][ 'payment_gateway' ]; //订单状态

               if ( !$s ) {
                   return '';
               } else {
                   return $e[ $s ];
               };
           },
           'label' => '付款方式'],

       ['attribute' => 'amount',
           'value' => 'order.order_amount',
           'contentOptions' => ['class' => 'text-right'],
           'label' => '合计'],

       ['class' => 'kartik\grid\CheckboxColumn'],

       ['class' => 'kartik\grid\ActionColumn',
           'template' => '{view}{update}',
           'buttons' => [
                   'view' => function($model, $url, $key){
                       return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['store-view', 'id' => $key]);
                   }
           ],
           'header' => '操<br />作'],
   ];
   echo GridView::widget([
       'dataProvider' => $dataProvider,
       'filterModel' => $search,
       'panel' => ['type' => 'info', 'heading' => '我的订单'],
       'columns' => $gridview
   ]);
?>
