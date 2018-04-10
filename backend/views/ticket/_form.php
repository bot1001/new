<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
	div{
		margin-left: 1%;
		b	#div1{margin-left: 1%;

		margin-left: -2%;
		width: 100%;
		margin: auto;
		background: #AEE8DC;
		border-radius: 20px;
	}
</style>

<div id="div1" class="ticket-basic-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
    	<div class="col-lg-4">
    		<?= $form->field($model, 'community_id')->textInput() ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'building')->textInput() ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'realestate_id')->textInput() ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-11">
    		<?= $form->field($model, 'explain1')->textArea(['maxlength' => true, 'rows' => 12, 'placeholder' => '请输入投诉内容……']) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-3">
    		<?= $form->field($model, 'contact_person')->textInput(['maxlength' => true, 'placeholder' => '请输入……']) ?>
    	</div>
    	<div class="col-lg-4">
    		<?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true, 'placeholder' => '请输入投诉人联系电话']) ?>
    	</div>
    </div>
        
    <div class="row">
    	<div class="col-lg-3">
    		<?= $form->field($model, 'assignee_id')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'ticket_status')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'tickets_taxonomy')->textInput() ?>
    	</div>
    </div>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
