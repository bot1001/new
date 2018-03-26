<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-account-form">

    <?php $form = ActiveForm::begin(); ?>
    
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'account_id')->hint('请输入32个用户串码（数字+拼音）')->textInput(['maxlength' => true,'readonly' =>true],['prompt' => '请输入']) ?>
		</div><div class="col-lg-3">
			<?= $form->field($model, 'password')->hint('默认密码：123456')->textInput(['readonly' => true,'readonly'=> true]) ?>
		</div><div class="col-lg-3">
			<?= $form->field($model, 'mobile_phone')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
    
    <?php // $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'qq_openid')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'weixin_openid')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'weibo_openid')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'account_role')->textInput() ?>

    <?php // $form->field($model, 'new_message')->textInput() ?>

    <?php // $form->field($model, 'property')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
