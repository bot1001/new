<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SysUser */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sys Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-user-view">

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
            'company',
            'community',
            'real_name',
            'name',
            'role',
            'phone',
            'password',
            'status',
            'comment',
            'salt',
            'create_id',
            'create_time',
            'update_time',
            'update_id',
            'new_pd',
        ],
    ]) ?>

</div>
