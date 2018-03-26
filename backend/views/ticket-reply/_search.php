<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketReplySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-reply-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'reply_id') ?>

    <?= $form->field($model, 'ticket_id') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'is_attachment') ?>

    <?php // echo $form->field($model, 'reply_time') ?>

    <?php // echo $form->field($model, 'reply_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
