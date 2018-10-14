<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = $model->store_id;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->store_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->store_id], [
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
            'store_id',
            'store_cover',
            'province_id',
            'city_id',
            'area_id',
            'store_name',
            'community_id',
            'store_address',
            'store_introduce',
            'store_phone',
            'store_longitude',
            'store_latitude',
            'add_time:datetime',
            'is_certificate',
            'store_sort',
            'store_status',
            'type',
            'store_taxonomy',
        ],
    ]) ?>

</div>
