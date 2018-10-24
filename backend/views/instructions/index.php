<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InstructionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作指南';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>

<div class="instructions-index">

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        'id',
        'author',
        'content',
        'create_time:datetime',
        'update_time:datetime',
        'type',
        'version',
        'property',

        ['class' => 'kartik\grid\ActionColumn',
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '指南文章列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
