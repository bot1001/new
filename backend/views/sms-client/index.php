<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmsClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-client-index">

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn'],

        ['attribute'=> 'name',
            'value' => 'user.name',
            'label' => '商户',
            'hAlign' => 'center'],

        ['attribute'=> 'type',
            'hAlign' => 'center'],

        ['attribute'=> 'community',
            'hAlign' => 'center'],

        ['attribute'=> 'count',
            'hAlign' => 'center'],

        ['attribute'=> 'surplus',
            'hAlign' => 'center'],

        ['attribute'=> 'status',
            'hAlign' => 'center'],

        ['attribute'=> 'property',
            'hAlign' => 'center'],


        ['class' => 'kartik\grid\ActionColumn'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '短信列表',
            'before' => Html::a('<span class="glyphicon glyphicon-shopping-cart"></span>', ['create'], ['class' => 'btn btn-success'])],
        'columns' => $gridview,
        'hover' => true,
    ]); ?>
</div>
