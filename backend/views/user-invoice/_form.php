<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'community_id')->textInput() ?>

    <?= $form->field($model, 'building_id')->textInput() ?>

    <?= $form->field($model, 'realestate_id')->textInput() ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'order_id')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'invoice_notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'payment_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_status')->textInput() ?>

    <?= $form->field($model, 'update_time')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
