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

        ['attribute' => 'title',
            'contentOptions' => ['class' => ['text-left']]
        ],
        ['attribute' => 'author',
            'value' => 'au.name'],

        ['attribute' => 'content',
            'value' => 'c',
            'contentOptions' => ['class' => ['text-left']]
        ],

        ['attribute' => 'create_time',
            'mergeHeader' => true],

        ['attribute' => 'update_time',
            'mergeHeader' => true],

        ['attribute' => 'sort',
            'class' => 'kartik\grid\EditableColumn',
            // 判断活动列是否可编辑
            'readonly' => function ( $model, $key, $index, $widget ) {
                return ( \app\models\Limit::limit($url = 'instructions/instructions') != 1 );
            },
            'editableOptions' => [
                'formOptions' => [ 'action' => [ '/instructions/instructions' ] ], // point to the new action
                'inputType' => \kartik\editable\ Editable::INPUT_TEXT,
            ],
        ],

        ['attribute' => 'version',
            'contentOptions' => [
                'class' => ['text-left']
            ]
        ],

        ['attribute' => 'type',
            'value' => function($model){
                return \common\models\Instructions::arr($one = 'type')[$model->type];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \common\models\Instructions::arr($one = 'type'),
            'filterInputOptions' => [ 'placeholder' => '请选择' ],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
            'class' => 'kartik\grid\EditableColumn',
            // 判断活动列是否可编辑
            'readonly' => function ( $model, $key, $index, $widget ) {
                return ( \app\models\Limit::limit($url = 'instructions/instructions') != 1 );
            },
            'editableOptions' => [
                'formOptions' => [ 'action' => [ '/instructions/instructions' ] ], // point to the new action
                'inputType' => \kartik\editable\ Editable::INPUT_DROPDOWN_LIST,
                'data' => \common\models\Instructions::arr($one = 'type'),
            ],
        ],



        ['attribute' => 'status',
            'value' => function($model){
                return \common\models\Instructions::arr($one = 'status')[$model->status];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \common\models\Instructions::arr($one = 'status'),
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                    'pluginOptions' => [
                            'allowClear' => true
                    ]
               ],
            'class' => 'kartik\grid\EditableColumn',
            // 判断活动列是否可编辑
            'readonly' => function ( $model, $key, $index, $widget ) {
                return ( \app\models\Limit::limit($url = 'instructions/instructions') != 1 );
            },
            'editableOptions' => [
                'formOptions' => [ 'action' => [ '/instructions/instructions' ] ], // point to the new action
                'inputType' => \kartik\editable\ Editable::INPUT_DROPDOWN_LIST,
                'data' => \common\models\Instructions::arr($one = 'status'),
            ],
        ],

        ['attribute' => 'property'],


        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{update}{view}',
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
