<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WaterMeter */

$this->title = 'Update Water Meter: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Water Meters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="water-meter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
