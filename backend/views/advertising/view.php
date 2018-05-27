<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */

$this->title = $model->ad_id;
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertising-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ad_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ad_id], [
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
            'ad_id',
            'ad_title',
            'ad_excerpt:ntext',
            'ad_poster',
            'ad_publish_community',
            'ad_type',
            'ad_target_value:ntext',
            'ad_location',
            'ad_created_time:datetime',
            'ad_sort',
            'ad_status',
            'property',
        ],
    ]) ?>

</div>
