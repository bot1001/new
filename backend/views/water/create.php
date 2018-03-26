<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WaterMeter */

$this->title = 'Create Water Meter';
$this->params['breadcrumbs'][] = ['label' => 'Water Meters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-meter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
