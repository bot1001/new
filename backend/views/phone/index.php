<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PhoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '便民电话';
$this->params['breadcrumbs'][] = $this->title;

//引入模态文件
echo $this->render('..\..\..\common\modal\modal.php');
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
            'template' => Helper::filterActionColumn('{update}{view}{delete}'),
            'buttons' => [
                'update' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#',[
                        'class' => 'pay',
                        'data-toggle' => 'modal',
                        'data-url' => Url::to(['update', 'id' => $key]),
                        'data-title' => '修改',
                        'data-target' => '#common-modal',
                    ]);
                },

                'view' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#',[
                        'class' => 'pay',
                        'data-toggle' => 'modal',
                        'data-url' => Url::to(['view', 'id' => $key]),
                        'data-title' => '修改',
                        'data-target' => '#common-modal',
                    ]);
                }
            ],
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '电话列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
                'class' => 'btn btn-success pay',
                'data-toggle' => 'modal',
                'data-url' => 'create',
                'data-title' => '创建', //如果不设置子标题，默认使用大标题
                'data-target' => '#common-modal',
            ])
        ],
        'columns' => $gridview,
    ]); ?>
</div>
