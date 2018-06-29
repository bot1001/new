<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SysUser */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
	#id{
		display: none;
	}
</style>

<div class="sys-user-form">

    <?php $form = ActiveForm::begin(); ?>
	<div id="id">
		<?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>
	</div>

    <?php // $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'role')->textInput() ?>

    <?php // $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new_pd')->textInput(['maxlength' => true, 'placeholder' => '请输入密码']) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
