<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Information */

$this->title = $model->remind_id;
$this->params['breadcrumbs'][] = ['label' => 'Informations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="information-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->remind_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->remind_id], [
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
            'remind_id',
            'room_name',
            'detail',
            'times:datetime',
            'reading',
            'target',
            'ticket_number',
            'remind_time',
            'property',
        ],
    ]) ?>

</div>
