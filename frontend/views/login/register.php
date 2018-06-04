<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */
/* @var $form yii\widgets\ActiveForm */

$this->title = '用户注册';
?>

<div class="login-form">

    <?php $form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_INLINE,
	'action' => ['/login/new','w_info' => $w_info],
]); ?>
	<div class="row">
	
	    <div class="col-lg-3">
			<?= $form->field($data, 'province_id')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($data, 'city_id')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($data, 'area_id')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	
	<div class="row">
		
		<div class="col-lg-3">
			<?= $form->field($realestate, 'community_id')->dropDownList($comm,['prompt' => '请选择','id'=>'community']) ?>
		</div>
		
		<div class="col-lg-1">
			<?= $form->field($realestate, 'building_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'building'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['community'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/realestate/b'])
                ]
            ]); ?>
		</div>
		
		<div class="col-lg-1">
			<?= $form->field($realestate, 'room_name')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'number'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['building'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/realestate/re'])
                ]
            ]); ?>
		</div>
		
		<div class="col-lg-2">
			<?php // $form->field($realestate, 'room_name')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($account, 'mobile_phone')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($account, 'password')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($account, 'weixin_openid')->textInput(['maxlength' => true, 'value' => $w_info['openid']]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($account, 'wx_unionid')->textInput(['maxlength' => true, 'value' => $w_info['unionid']]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($data, 'face_path')->textInput(['maxlength' => true, 'value' => $w_info['headimgurl']]) ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($data, 'gender')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-lg-3">
			<div class="form-group" align="center">
                <?= Html::submitButton($account->isNewRecord ? '确定' : 'Update', ['class' => $account->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
		</div>
	</div>
    
    <?php ActiveForm::end(); ?>
   
</div>