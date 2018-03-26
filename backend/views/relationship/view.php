<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserRelationshipRealestate */

$this->title = $model->account_id;
$this->params['breadcrumbs'][] = ['label' => 'User Relationship Realestates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-relationship-realestate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('设置业主姓名', ['account-data/create', 'account_id' => $model->account_id,'realestate_id' =>$model->realestate_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'account_id',
            'realestate_id',
        ],
    ]) ?>

</div>
