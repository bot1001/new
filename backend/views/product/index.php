<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],
        ['attribute' => 'store',
            'value' => 'store.store_name',
            'label' => '商城',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'product_name',
            'contentOptions' => ['class' => 'text-left']],
        ['attribute' => 'product_subhead',
            'contentOptions' => ['class' => 'text-left']],

//        ['attribute' => 'product_taxonomy'],
//        ['attribute' => 'brand_id'],
        ['attribute' => 'market_price', 'contentOptions' => ['class' => 'text-right']],

        ['attribute' => 'product_price',
            'contentOptions' => ['class' => 'text-right']],
        ['attribute' => 'product_image',
            'format' => ['image',
                [
                        'height' =>'30px',
                ]],
            'value' => function($model){
                return "http://epmscos3-10009107.image.myqcloud.com/".$model->product_image;
            },
            'header' => '缩略图',
            'mergeHeader' => true],

//        ['attribute' => 'product_introduction'],
        ['attribute' => 'product_quantity',
            'contentOptions' => ['class' => 'text-right']],

        ['attribute' => 'product_status',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['1' => '上架', '2' => '下架', '3' => '待审核', '4' => '审核中'],
            'filterInputOptions' => ['placeholder' => '…'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            'value' => function($model){
                $date = ['1' => '上架', '2' => '下架', '3' => '待审核', '4' => '审核中'];
                return $date[$model->product_status];
            }],

        ['class' => 'kartik\grid\ActionColumn', 'header' => '操<br />作'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '产品列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview
    ]); ?>
</div>
