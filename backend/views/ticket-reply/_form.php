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

    <?= $form->field($model, 'account_id')->dropDownList(\app\models\UserAccount::getAccount(),['prompt' => '请选择']) ?>

    <?= $form->field($model, 'content')->textArea(['maxlength' => true, 'max' => 5]) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('回复', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
