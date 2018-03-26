<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-basic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_parent')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'order_type')->textInput() ?>

    <?= $form->field($model, 'payment_time')->textInput() ?>

    <?= $form->field($model, 'payment_gateway')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
