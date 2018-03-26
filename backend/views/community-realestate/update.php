<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestate */

$this->title = '更新: ' . $model->room_number;
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->room_number, 'url' => ['view', 'id' => $model->realestate_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="community-realestate-update">

    <h1><?php // echo Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
