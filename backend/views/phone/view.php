<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */

$this->title = $model->phone_id;
$this->params['breadcrumbs'][] = ['label' => 'Phone Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-list-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->phone_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->phone_id], [
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
            'phone_id',
            'phone_name',
            'phone_number',
            'parent_id',
            'have_lower',
            'phone_sort',
        ],
    ]) ?>

</div>
