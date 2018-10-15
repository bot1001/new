<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaxonomySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '行业或类别';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="store-taxonomy-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        [ 'attribute' => 'name',
            'contentOptions' => ['class' => 'text-left']],

        [ 'attribute' => 'type',
            'value' => function($model){
                $date = ['1' => '商店', '2' => '商品'];
                return $date[$model->type];
            },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['taxonomy/taxonomy']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => ['1' => '商店', '2' => '商品'],
            ],
//            'readonly' => function($model){
//                return $model->store_status == '0';
//            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['1' => '商店', '2' => '商品'],
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ]
        ],

        [ 'attribute' => 'creator',
            'value' => 'creator0.name'],
        [ 'attribute' => 'sort',
            'mergeHeader' => true],
        [ 'attribute' => 'create_time',
            'mergeHeader' => true],
        [ 'attribute' => 'property'],

        ['class' => 'kartik\grid\ActionColumn',
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '行业或类别列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
