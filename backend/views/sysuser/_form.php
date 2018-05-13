<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SysUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-user-form">
   
  <style>
	  #create{
		  #background: #7BD8BC;
		  position: relative;
		  margin-right: -20%;
	  }
	</style>
	   
   
    
    <?php $form = ActiveForm::begin(); ?>
    <div id="create">
	<div class="row">
		<div class="col-lg-4">
			<?= $form->field($model, 'company')->dropDownList($company,['prompt'=>'请选择','id'=>'company']) ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'community')->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'community'],
	                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['company'],
                            'placeholder'=>'请选择...',
                            'url'=>Url::to(['/company/c'])
                        ]
                    ],['multiple'=>'multiple']) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($model, 'role')->dropDownList($role, ['prompt' => '请选择'])->label('数据角色') ?>
		</div>
	</div>
    
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeHolder' => '请输入用户名']) ?>
		</div>
		
		<div class="col-lg-4">
			<?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeHolder' => '请输入手机号码']) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($model, 'status')->dropDownList(['1' => '正常', '2' => '锁定'], ['prompt' => '请选择']) ?>
		</div>
	</div>
    
	<div class="row">
		<div class="col-lg-10">
			<?= $form->field($model, 'new_pd')->passwordInput(['maxlength' => true, 'placeHolder' => '请输入密码']) ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-10">
			<?= $form->field($model, 'n')->passwordInput(['maxlength' => true, 'placeHolder' => '请重新输入密码'])->label('重新输入密码') ?> 
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-10">
			<?= $form->field($model, 'comment')->textArea(['maxlength' => true, 'placeHolder' => '请输入备注……']) ?>
		</div>
	</div>
	
    </div>
    
    <div class="form-group" align="center">
        <?= Html::submitButton($model->isNewRecord ? '确定' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
</div>
