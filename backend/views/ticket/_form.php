<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-basic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ticket_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'community_id')->textInput() ?>

    <?= $form->field($model, 'realestate_id')->textInput() ?>

    <?= $form->field($model, 'tickets_taxonomy')->textInput() ?>

    <?= $form->field($model, 'explain1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_attachment')->textInput() ?>

    <?= $form->field($model, 'assignee_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reply_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticket_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remind')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
