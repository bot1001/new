<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sys Communities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-community-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'sys_user_id',
            'community_id',
            'own_add',
            'own_delete',
            'own_update',
            'own_select',
        ],
    ]) ?>

</div>
