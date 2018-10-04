<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '确认';
$this->params['breadcrumbs'][] = $this->title;

//引入模态窗文件
echo $this->render('..\..\..\common\modal\modal.php');
?>
<style>
    .products-index{
        width: 400px;
        border: solid #00a0e9;
        border-radius: 10px;
    }
    th, td{
        text-align: center;
    }
    .sure{
        text-align: center;
        font-size: 40px;
    }
</style>
<div class="products-index">

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序号'],
        ['attribute' => 'product_name',
            'pageSummary' => '合计：'],

        ['attribute' => 'product_price',
            'pageSummary' => true],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}",
        'showPageSummary' => true,
        'columns' => $gridview,
    ]); ?>

    <div class="sure">
        <?= Html::a('<span class="glyphicon glyphicon-new-window"></span>', '#', [
            'class' => 'btn btn-success pay',
            'data-toggle' => 'modal',
            'data-url' => "/order/create?order=$order&realestate=$realestate",
            'data-title' => '支付', //如果不设置子标题，默认使用大标题
            'data-target' => '#common-modal',
        ]) ?>
    </div>
</div>
