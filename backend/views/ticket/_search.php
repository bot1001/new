<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-basic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ticket_id') ?>

    <?= $form->field($model, 'ticket_number') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'realestate_id') ?>

    <?php // echo $form->field($model, 'tickets_taxonomy') ?>

    <?php // echo $form->field($model, 'explain1') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'contact_person') ?>

    <?php // echo $form->field($model, 'contact_phone') ?>

    <?php // echo $form->field($model, 'is_attachment') ?>

    <?php // echo $form->field($model, 'assignee_id') ?>

    <?php // echo $form->field($model, 'reply_total') ?>

    <?php // echo $form->field($model, 'ticket_status') ?>

    <?php // echo $form->field($model, 'remind') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
