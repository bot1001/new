<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信模板';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],

        'sign_name',
        'sms',
        ['attribute' => 'count', 'hAlign' => 'center'],
        'creator',

        ['attribute' => 'create_time', 'hAlign' => 'center'],
        'property',

        ['class' => 'kartik\grid\ActionColumn', 'header' => '操<br />作'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '短信模板',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
