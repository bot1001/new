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

echo $this->render(Yii::$app->params['modal']);
?>

<style>
    .taxonomy{
        background: white;
        width: 800px;
    }

    th, td{
        text-align: center;
    }
    .tax-menu{
        text-align: center;
        position: relative;
        float: bottom;
        bottom: 10px;
    }
</style>
<div class="taxonomy-index">
    <?php $type = Yii::$app->params['taxonomy']['type']; ?>

    <div class="taxonomy">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => ['type' => 'success', 'heading' => '产品系列',
            ],
            'toolbar' => [],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => '序<br />号'],

                ['attribute' => 'parent',
                    'value' => 'tax.name',
                    'contentOptions' => ['class' => 'text-left'],
                    'label' => '品牌'],

                [ 'attribute' => 'name',
                    'contentOptions' => ['class' => 'text-left']
                ],

                ['class' => 'kartik\grid\ActionColumn',
                    'template' => Helper::filterActionColumn('{update}'),
                    'buttons' =>[
                        'update' => function($model){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#',
                                [
                                    'class' => 'pay',
                                    'data-toggle' => 'modal',
                                    'data-url' => Url::toRoute([$model.'&type=-2']),
                                    'data-title' => '添加行业', //如果不设置子标题，默认使用大标题
                                    'data-target' => '#common-modal',
                                ]);
                        }
                    ],
                    'header' => '操<br />作'],
            ],
            'pjax' => true
        ])
        ?>
        <div class="tax-menu">
            <?php
            if(Helper::checkRoute('create')){
                echo Html::a('<span class="glyphicon glyphicon-plus"></span>', '#',
                    [
                        'class' => 'btn btn-info pay',
                        'data-toggle' => 'modal',
                        'data-url' => Url::toRoute(['create', 'type' => '-2']),
                        'data-title' => '修改', //如果不设置子标题，默认使用大标题
                        'data-target' => '#common-modal',
                    ]);
            } ?>
        </div>
    </div>
</div>
