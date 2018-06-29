<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
//use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="house-info-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
    	<div class="col-lg-4">
    		<?= $form->field($model, 'community')->dropDownList($community, ['prompt' => '请选择', 'id'=> 'community'])->label('小区') ?>
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
                        ])->label('楼宇'); ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'realestate')->widget(DepDrop::classname(), [
                            'type' => DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'room_name'],
	                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                'depends'=>['building'],
                                'placeholder'=>'请选择...',
                                'url'=>Url::to(['/costrelation/re'])
                            ]
                        ])->label('房号') ?>
    	</div>
    </div>

    <div class="row">
    	<div class="col-lg-3">
    		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-lg-4">
    		<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    	</div>
    	
    	<div class="col-lg-3">
    		<?= $form->field($model, 'politics')->dropDownList(['0' => '群众', '1'=> '团员', '2'=> '党员']) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-7">
    		<?= $form->field($model, 'IDcard')->textInput(['length' => [15, 20]]) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-8">
    		<?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
    	</div>
	</div>
  	
    <div class="row">
    	<div class="col-lg-10">
    		<?= $form->field($model, 'property')->textArea(['maxlength' => true]) ?>
    	</div>
    </div>

    

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
