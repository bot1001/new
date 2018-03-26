<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommunityBasic;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-building-form">

   <?php
	    $community = CommunityBasic::find()
			->select('community_name,community_id')
			->asArray()
			->all(); 
	$com = ArrayHelper::map($community, 'community_id', 'community_name');
	?>
    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'community_id')->dropDownList($com,['prompt' => '请选择']) ?>

    <?= $form->field($model, 'building_name')->textInput(['maxlength' => true, 'placeHolder' => '请输入房号']) ?>

    <?php // $form->field($model, 'building_parent')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'creater')->textInput() ?>

    <?php // $form->field($model, 'create_time')->textInput() ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
