<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SysUser;
use app\models\CommunityBasic;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-community-form">

   <?php
	if($id != '0'){
		//获取用户
		$sys = SysUser::find()
			->select('name, id')
			->where(['id' => "$id", 'status' => '1'])
			->orderBy('name')
			->asArray()
			->indexBy('id')
			->column();
		}else{
		$sys = SysUser::find()
			->select('name, id')
			->where(['status' => '1'])
			->orderBy('name')
			->asArray()
			->indexBy('id')
			->column();
	}
		//关联小区
		$comm = CommunityBasic::find()
			->select('community_name, community_id')
			->orderBy('community_name DESC')
			->asArray()
			->indexBy('community_id')
			->column();
	?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sys_user_id')->dropDownList($sys); ?>

    <?= $form->field($model, 'community_id')->checkBoxList($comm) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
