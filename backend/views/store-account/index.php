<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StoreAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商城用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-account-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <style>
        th,td{
            text-align: center;
        }
    </style>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        ['attribute' =>  'user_id'],
        ['attribute' =>  'work_number'],
        ['attribute' =>  'store_id'],
        ['attribute' =>  'role'],
        ['attribute' =>  'status'],
        ['attribute' =>  'property'],

        ['class' => 'kartik\grid\ActionColumn',
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '商城用户列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
