<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommunityBasic;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-building-form">

   <?php
	    $com = CommunityBasic::find()
			->select('community_name,community_id')
			->indexBy('community_id')
			->column();
	
	$company = Company::find()->select('name, id')->indexBy('id')->column() ;
	?>
   
    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'company')->dropDownList($company,['prompt' => '请选择']) ?>
    
    <?= $form->field($model, 'community_id')->dropDownList($com,['prompt' => '请选择']) ?>

    <?= $form->field($model, 'building_name')->textInput(['maxlength' => true, 'placeHolder' => '请输入房号']) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
