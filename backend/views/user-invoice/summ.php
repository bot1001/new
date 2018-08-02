<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<?php $this->title = '按小区'; ?>

<div>
    <?php
    $gridColumn = [
        [ 'class' => 'kartik\grid\SerialColumn',
            'header' => '序号'
        ],
        ['attribute' => 'id',
        'label' => '费项编号'],
        ['attribute' => 'building',
        'label' => '楼宇'],
        ['attribute' => 'number',
        'label' => '单元'],
        ['attribute' => 'name',
        'label' => '房号'],
        ['attribute' => 'year',
        'label' => '年份'],
        ['attribute' => 'month',
        'label' => '月份'],
        ['attribute' => 'description',
        'label' => '详情',
        'contentOptions' => ['class' => 'text-left'],
        ],

        ['attribute' => 'amount',
        'label' => '金额',
            'contentOptions' => ['class'=>'text-right']
        ],

        ['attribute' => 'order',
            'value' => function($dataProvider){
                if(empty($dataProvider['order'])){
                    return '';
                }else{
                    return $dataProvider['order'];
                }
            },
            'label' => '订单编号'],

        ['attribute' => 'payment_time',
            'value' => function($dataProvider){
                if(empty($dataProvider['payment_time'])){
                    return '';
                }else{
                    return date('Y-m-d H:i:s', $dataProvider['payment_time']);
                }
            },
            'label' => '支付时间'],

        ['attribute' => 'status',
            'value' => function($dataProvider){
                $date = [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ];
                return $date[$dataProvider['status']];
            },
            //单元格背景色变换
            'contentOptions' => function ( $dataProvider ) {
                return ( $dataProvider['status'] == 0 ) ? [ 'class' => 'bg-orange' ] : [];
            },
        'label' => '状态'],
    ];
    echo GridView::widget( [
        'dataProvider' => $dataProvider,
        'panel' => ['type' => 'primary', 'heading' => '合计：'.$sum.' 元；'.'起始时间：'.$from.'&nbsp&nbsp&nbsp&nbsp'.'截止时间：'.$to],
        'columns' => $gridColumn,
        'toolbar' =>[
                '{export}'
        ],
        'pjax'=>true,
        'hover' => true,
    ]);
    ?>
</div>
