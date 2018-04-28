<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\CommunityBasic;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-news-form">

   <?php
	$c = $_SESSION['user']['community'];
	if(empty($c)){
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->asArray()
			->all();
	}else{
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->where(['community_id' => $c])
			->asArray()
			->all();
	}
	 $comm = ArrayHelper::map($community,'community_id', 'community_name');
	?>
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
    	<div class="col-lg-12">
    		<?= $form->field($model, 'community_id')->dropDownList($comm) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-12">
    		<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-12">
    		<?= $form->field($model, 'excerpt')->textInput(['rows' => 6]) ?>
    	</div>
    </div>

    <?php
	if(empty($model->content)){
		echo $form->field($model, 'content')->textarea(['rows' => 12, 'value' => "尊敬的业主：
	    您们好！
		"]);
	}else{
		echo $form->field($model, 'content')->textarea(['rows' => 12]);
	}  ?>

    <?php // $form->field($model, 'post_time')->textInput() ?>

    <?php // $form->field($model, 'update_time')->textInput() ?>

    <?php // $form->field($model, 'view_total')->textInput() ?>

    <div class="row">
    	<div class="col-lg-3">
    		<?= $form->field($model, 'stick_top')->dropDownList(['不置顶', '置顶']) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'status')->dropDownList([ '1'=> '正常', '2' => '预发布', '3' => '过期']) ?>
    	</div>
    </div>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
