<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */

$this->title = $model->building_id;
$this->params['breadcrumbs'][] = ['label' => '楼宇列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-building-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Update', ['update', 'id' => $model->building_id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a('Delete', ['delete', 'id' => $model->building_id], [
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
            'building_id',
            'community_id',
            'building_name',
            //'building_parent',
            'creater',
            ['attribute' => 'create_time',
		    	'value' => function($model){
		         	return date('Y-m-d H:i:s', $model->create_time);
		        }],
            ],
    ]) ?>

</div>
