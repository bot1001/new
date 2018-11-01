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
                    'template' => '{update}',
                    'header' => '操<br />作'],
            ],
            'pjax' => true
        ])
        ?>
        <div class="tax-menu">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create', 'id' => '3'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>
