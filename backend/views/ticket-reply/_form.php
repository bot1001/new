<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-reply-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ticket_id')->textInput() ?>

    <?= $form->field($model, 'account_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_attachment')->textInput() ?>

    <?= $form->field($model, 'reply_time')->textInput() ?>

    <?= $form->field($model, 'reply_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
