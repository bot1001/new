<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="house-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'realestate')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IDcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creater')->textInput() ?>

    <?= $form->field($model, 'create')->textInput() ?>

    <?= $form->field($model, 'update')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'politics')->textInput() ?>

    <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
