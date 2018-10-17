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

        ['attribute' =>  'user_id',
            'value' => 'user.name',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' =>  'work_number',
            'contentOptions' => ['class' => 'text-left']],
        ['attribute' =>  'store_id',
            'value' => 'store.store_name',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' =>  'role',
            'value' => function($model){
                $date = ['1' => '店长', '2' => '管理员', 3=> '职员'];
                return $date[$model->role];
            }],

        ['attribute' =>  'status',
            'value' => function($model){
                $date = ['0' => '禁用', '1' => '启用', '2' => '锁定', 3=> '其他'];
                return $date[$model->status];
            }],

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
