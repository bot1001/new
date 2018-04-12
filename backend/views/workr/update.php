<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorkR */

$this->title = 'Update Work R: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Work Rs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="work-r-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
