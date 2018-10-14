<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phone-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_number')->textInput() ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'have_lower')->textInput() ?>

    <?= $form->field($model, 'phone_sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
