<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
		
	#div1{
        margin-left: 1%;
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
    		<?= $form->field($model, 'community_id')->dropDownList($community,['prompt' => '请选择', 'id' => 'community']) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'building')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'building'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['community'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/b'])
                ]
            ]); ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'realestate_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'realestate'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['building'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/re'])
                ]
            ]); ?>
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
    		<?= $form->field($model, 'assignee_id')->dropDownList($assignee,['placeholder' => '请选择']) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'ticket_status')->dropDownList([1=> '未处理', 6 => '处理中',3 => '已完成', 4 => '返修']) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'tickets_taxonomy')->dropDownList([1 => '建议', 2 => '投诉']) ?>
    	</div>
    </div>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
