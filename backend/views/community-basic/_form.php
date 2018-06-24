<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-basic-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'company')->dropDownList(\app\models\Company::getCompany(), ['maxlength' => true, 'prompt' => '请选择']) ?>
    <?= $form->field($model, 'community_name')->textInput(['maxlength' => true]) ?>
    
    <div class="row">
    	<div class="col-lg-4">
    		<?= $form->field($model, 'province_id')->dropDownList(\common\models\Area::getProvince(), ['maxlength' => true, 'prompt' => '请选择', 'id' => 'province']) ?>
    	</div>
    	
    	<div class="col-lg-4">
    		<?= $form->field($model, 'city_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'options'=>['id'=>'city'],
	                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                                'pluginOptions'=>[
                                    'depends'=>['province'],
                                    'placeholder'=>'请选择...',
                                    'url'=>Url::to(['/area/city'])
                                ]
                            ]); ?>
    	</div>
    	
    	<div class="col-lg-4">
    		<?= $form->field($model, 'area_id')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'options'=>['id'=>'area'],
	                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                                'pluginOptions'=>[
                                    'depends'=>['city'],
                                    'placeholder'=>'请选择...',
                                    'url'=>Url::to(['/area/city'])
                                ]
                            ]); ?>
    	</div>
    </div>
    
    <?= $form->field($model, 'community_address')->textInput(['maxlength' => true, 'placeHolder' => '请输入地址……']) ?>

    <div class="row">
    	<div class="col-lg-4">
    		<?= $form->field($model, 'community_logo')->textInput(['maxlength' => true, 'placeHolder' => '请输入LOGO（可为空）']) ?>
    	</div>
    	
    	<div class="col-lg-4">
    		<?= $form->field($model, 'community_longitude')->textInput(['maxlength' => true, 'placeHolder' => '请输入经度（可为空）']) ?>
    	</div>
    	
    	<div class="col-lg-4">
    		<?= $form->field($model, 'community_latitude')->textInput(['maxlength' => true, 'placeHolder' => '请输入纬度（可为空）']) ?>
    	</div>
    </div>

  
    <div class="form-group" align="center">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
