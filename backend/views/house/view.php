<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-view">

    <h1><?php if(isset($room)){
        echo $room;
        }  ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->house_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->house_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'house_id',
            'c.community_name',
            'name',
            'phone',
            'IDcard',
            'creater',
            'create',
            'update',
            'status',
            'address',
            'politics',
            'property',
        ],
    ]) ?>

</div>
