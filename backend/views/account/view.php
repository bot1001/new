<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => '内部用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .user-account-view{
        width: 500px;
        background:#ffffff;
        border-radius: 15px;
        min-height: 300px;
    }
    .user-account{
        width: 90%;
        margin: auto;
        position: relative;
        top: 20px;
    }
</style>
<div class="user-account-view">

    <div class="user-account">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('设置关联小区', ['workr/create', 'user_id' => $model->user_id], ['class' => 'btn btn-info']) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'work.work_number',
                'user_name',
                'mobile_phone',
                'status',
            ],
        ]) ?>
    </div>


</div>
