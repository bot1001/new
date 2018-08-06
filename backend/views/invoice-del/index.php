<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceDelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '删除记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="invoice-del-index">

    <?php
    $gridview =[
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],
        ['attribute' => 'community',
            'value' => 'community.community_name',
            'label' => '小区'],

        ['attribute' => 'building',
            'value' => 'building.building_name',
            'label' => '楼宇'],

        ['attribute' => 'number',
            'value' => 'realestate.room_number',
            'label' => '单元'],

        ['attribute' => 'name',
            'value' => 'realestate.room_name',
            'label' => '房号'],

        ['attribute' => 'description',],
        ['attribute' => 'year',],
        ['attribute' => 'month',],
        ['attribute' => 'invoice_amount',],
        ['attribute' => 'order_id',],
        ['attribute' => 'payment_time',],
        ['attribute' => 'invoice_notes',],
        ['attribute' => 'user',
            'value' => 'user.name',
            'label' => '操作人'],
        ['attribute' => 'update_time',],

//        ['class' => 'kartik\grid\ActionColumn'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridview,
    ]); ?>
</div>
