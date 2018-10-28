<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccumulateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '积分记录';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th,td{
        text-align: center;
    }
</style>

<div class="accumulate-index">

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        ['attribute' => 'name',
            'value' => 'address.name',
            'contentOptions' => ['class' => ['text-left']],
            'label' => '姓名'
        ],

        ['attribute' => 'amount',
            'contentOptions' => ['class' => ['text-right']]
        ],

        ['attribute' => 'income',
            'value' => function($model){
                return \common\models\Accumulate::arr($one = 'income')[$model->income];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \common\models\Accumulate::arr($one = 'income'),
            'filterInputOptions' => [ 'placeholder' => '请选择' ],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
            ],

        ['attribute' => 'order_id'],

        ['attribute' => 'type',
            'value' => function($model){
                return \common\models\Accumulate::arr($one = 'type')[$model->type];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \common\models\Accumulate::arr($one = 'type'),
            'filterInputOptions' => [ 'placeholder' => '请选择' ],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
        ],

        ['attribute' => 'create_time'],

        ['attribute' => 'status',
            'value' => function($model){
                return \common\models\Accumulate::arr($one = 'status')[$model->status];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \common\models\Accumulate::arr($one = 'status'),
            'filterInputOptions' => [ 'placeholder' => '请选择' ],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
        ],
        ['attribute' => 'property'],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => Helper::filterActionColumn('{view}{update}{delete}'),
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=> ['type' => 'info', 'heading' => '积分记录'],
        'columns' => $gridview,
    ]); ?>
</div>
