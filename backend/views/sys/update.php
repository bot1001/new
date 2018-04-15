<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */

$this->title = 'Update Sys Community: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sys Communities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sys-community-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
