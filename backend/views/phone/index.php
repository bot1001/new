<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PhoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '便民电话';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .phone-list-index{
        max-width: 500px;
    }
</style>
<div class="phone-list-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        ['attribute' => 'phone_name'],
        ['attribute' => 'phone_number'],
        ['attribute' => 'parent_id',
            'value' => 'phone.phone_name'],

//            ['attribute' => 'have_lower'],
        ['attribute' => 'phone_sort'],

        ['class' => 'kartik\grid\ActionColumn',
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '电话列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
