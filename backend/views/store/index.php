<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商城列表';
$this->params['breadcrumbs'][] = $this->title;

//引入模态框文件
echo $this->render(Yii::$app->params['modal']);
?>
<div class="store-index">
<style>
    th, td{
        text-align: center;
    }
</style>
    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

//            'store_id',
        ['attribute' => 'store_name',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                    'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->store_status == '0';
            },
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'store_cover',
            'format' => 'raw',
            'value' => function($model, $key){
                $url = $model->store_cover;
                return Html::a( "<img src='$url' style='width: 35px' alt = '更新'/>", '#',
                    [
                        'class' => 'pay',
                        'alt' => '更新',
                        'style'=> 'color: red',
                        'data-toggle' => 'modal',
                        'data-url' => Url::toRoute(['img', 'id' => $model['store_id'], 'image' => $url]),
                        'data-title' => '更新图片',
                        'data-target' => '#common-modal'
                    ]
                );
            },
            'header' => '缩略图',
            'width' => '80px',
            'mergeHeader' => true],

        ['attribute' => 'province_id',
            'value' => 'province.area_name',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'city_id',
            'value' => 'city.area_name',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'area_id',
            'value' => 'area.area_name',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'store_address',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->store_status == '0';
            },
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'store_phone',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->store_status == '0';
            },
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'add_time',
            'mergeHeader' => true],

//        ['attribute' => 'store_sort'],
        ['attribute' => 'store_status',
            'value' => function($model){
                $date = [0 => '禁用', 1 => '启用', 2=>'待审核', 3 => '锁定'];
                return $date[$model->store_status];
            },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => [0 => '禁用', 1 => '启用', 2=>'待审核', 3 => '锁定']
            ],
//            'readonly' => function($model){
//                return $model->store_status == '0';
//            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [0 => '禁用', 1 => '启用', 2=>'待审核', 3 => '锁定'],
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ]
        ],

        ['attribute' => 'type',
            'value' => function($model){
                $date = ['1' => '大型', 2 => '小型'];
                return $date[$model->type];
            },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => ['1' => '大型', 2 => '小型']
            ],
            'readonly' => function($model){
                return $model->store_status == '0';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [1 => '大型', 2 => '小型'],
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true]
            ]
            ],

        ['attribute' => 'store_taxonomy',
            'value' => 'taxonomy.name',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['store/store']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => $taxonomy,
            ],
            'readonly' => function($model){
                return $model->store_status == '0';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $taxonomy,
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ]],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{view}{update}',
            'header' => '操<br />作'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '商城列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info', 'title' => '添加'])],
        'columns' => $gridview,
    ]); ?>
</div>
