<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CostName */

$this->title = $model->cost_name;
$this->params['breadcrumbs'][] = ['label' => '费项列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-name-view">

    <h1><?php // Html::encode($this->title) ?></h1>

    <p align="right">
        <?= Html::a('更新', ['update', 'id' => $model->cost_id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a('Delete', ['delete', 'id' => $model->cost_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cost_id',
            'cost_name',
			'level',
            'price',
			'parent',
            ['attribute'=>'inv',
			'value'=>function($model){
			return $model->inv == 0 ? '否' : '是';
		}],
            'property',
        ],
    ]) ?>

</div>
