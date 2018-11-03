<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列表';
$this->params['breadcrumbs'][] = $this->title;

//引入模态框文件
echo $this->render(Yii::$app->params['modal']);
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $status = Yii::$app->params['product']['status'];

    $gridview = [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],
        ['attribute' => 'store',
            'value' => 'store.store_name',
            'label' => '商城',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-left']
        ],

        ['attribute' => 'product_name',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'product_subhead',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'product_image',
            'format' => 'raw',
            'value' => function($model){
                $url = Yii::$app->request->hostInfo.$model->product_image;
                return Html::a("<img src='$url', style='height: 30px; border-radius: 5px' alt='更新' />", '#',
                    [
                        'class' => 'pay',
                        'data-toggle' => 'modal',
                        'data-url' => Url::toRoute(['img', 'id' => $model->product_id, 'image' => $url, 'type' => 'product']),
                        'data-title' => '修改产品缩略图',
                        'data-target' => '#common-modal'
                    ]);
            },
            'header' => '缩略图',
            'mergeHeader' => true],

        ['attribute' => 'market_price',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-right']
        ],

        ['attribute' => 'product_sale',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-right']
        ],

        ['attribute' => 'product_accumulate',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_TEXT,
            ],
            'readonly' => function($model){
                return $model->product_status == '2';
            },
            'contentOptions' => ['class' => 'text-right']
        ],

        ['attribute' => 'product_status',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $status,
            'filterInputOptions' => ['placeholder' => '…'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            'value' => function($model, $status){
                $date = Yii::$app->params['product']['status'];
                return $date[$model->product_status];
            },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'formOptions' => ['action' => ['product/product']],
                'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => $status
            ],
            'readonly' => function($model){
//                if($model->product_status == '3')
                return $model->product_status == '3';
            },
            'width' => '90px'
        ],

        'reading',

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{view}{update}',
            'header' => '操<br />作'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '产品列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['class' => 'btn btn-info'])],
        'columns' => $gridview
    ]); ?>
</div>
