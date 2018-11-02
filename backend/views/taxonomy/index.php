<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaxonomySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '行业或类别';
$this->params['breadcrumbs'][] = $this->title;

//引入模态框文件
echo $this->render(Yii::$app->params['modal']);
?>

<style>
    #taxonomy{
        width: 400px;
        height: 450px;
        background: white;
        margin-left: 10px;
        border-radius: 5px;
        /*border: solid 1px gray;*/
    }
    .tax-one{
        height: 400px;
        overflow-y: auto;
    }
    th, td{
        text-align: center;
    }
    .tax-menu{
        text-align: center;
        position: relative;
        float: bottom;
        bottom: -10px;
    }
</style>
<div class="taxonomy-index row">
    <?php $type = Yii::$app->params['taxonomy']['type']; ?>

    <div class="taxonomy">
        <div id="taxonomy" class="col-lg-4">
            <div class="tax-one">
                <?php
                $gridview = [
                    ['class' => 'kartik\grid\SerialColumn',
                        'header' => '序<br />号'],

                    [ 'attribute' => 'name',
                        'contentOptions' => [
                                'class' => 'text-left'
                        ],
                        'label' => '类别',
                    ],

                    ['class' => 'kartik\grid\ActionColumn',
                        'template' => Helper::filterActionColumn('{update}'),
                        'buttons' =>[
                                'update' => function($model){
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#',
                                        [
                                            'class' => 'pay',
                                            'data-toggle' => 'modal',
                                            'data-url' => Url::toRoute([$model.'&type=0']),
                                            'data-title' => '修改', //如果不设置子标题，默认使用大标题
                                            'data-target' => '#common-modal',
                                        ]);
                                }
                        ],
                        'header' => '操<br />作'],
                ];

                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => ['type' => 'info', 'heading' => '行业'],
                    'toolbar' => [],
                    'pjax' => true,
                    'columns' => $gridview,
                ]); ?>
            </div>

            <div class="tax-menu">
                <?php
                if(Helper::checkRoute('create')){
                    echo Html::a('<span class="glyphicon glyphicon-plus"></span>', '#',
                        [
                            'class' => 'btn btn-info pay',
                            'data-toggle' => 'modal',
                            'data-url' => Url::toRoute(['create', 'type' => '0']),
                            'data-title' => '添加', //如果不设置子标题，默认使用大标题
                            'data-target' => '#common-modal',
                        ]);
                } ?>
            </div>

        </div>

        <div id="taxonomy" class="col-lg-4">
            <div class="tax-one">
                <?=  GridView::widget([
                    'dataProvider' => $data,
                    'filterModel' => $searchModel,
                    'panel' => ['type' => 'danger', 'heading' => '品牌',
                    ],
                    'toolbar' => [],
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn',
                            'header' => '序<br />号'],

                        [ 'attribute' => 'name',
                            'contentOptions' => ['class' => 'text-left'],
                            'label' => '品牌名称',
                        ],

                        ['attribute' => 'parent',
                            'value' => 'tax.name',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\StoreTaxonomy::Taxonomy($type = '0'),
                            'filterWidgetOptions' => [
                                'pluginOptions' => [ 'allowClear' => true ],
                            ],
                            'filterInputOptions' => [ 'placeholder' => '请选择' ],
                            'contentOptions' => ['class' => 'text-left'],
                            'label' => '类别',],

                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => Helper::filterActionColumn('{update}'),
                            'buttons' =>[
                                'update' => function($model){
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#',
                                        [
                                            'class' => 'pay',
                                            'data-toggle' => 'modal',
                                            'data-url' => Url::toRoute([$model.'&type=-1']),
                                            'data-title' => '修改', //如果不设置子标题，默认使用大标题
                                            'data-target' => '#common-modal',
                                        ]);
                                }
                            ],
                            'header' => '操<br />作'],
                    ],
                    'pjax' => true
                ])
                ?>
            </div>
            <div class="tax-menu">
                <?php
                if(Helper::checkRoute('create')){
                    echo Html::a('<span class="glyphicon glyphicon-plus"></span>', '#',
                        [
                            'class' => 'btn btn-info pay',
                            'data-toggle' => 'modal',
                            'data-url' => Url::toRoute(['create', 'type' => '-1']),
                            'data-title' => '添加行业', //如果不设置子标题，默认使用大标题
                            'data-target' => '#common-modal',
                        ]);
                }
                 ?>
            </div>
        </div>
    </div>
</div>
