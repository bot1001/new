<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WaterMeter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="water-meter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'realestate_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'readout')->textInput() ?>

    <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
