<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */

$this->title = $model->community_id;
$this->params['breadcrumbs'][] = ['label' => 'Community Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->community_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->community_id], [
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
            'community_id',
            'community_name',
            'community_logo',
            'province_id',
            'city_id',
            'area_id',
            'community_address',
            'community_longitude',
            'community_latitude',
        ],
    ]) ?>

</div>
