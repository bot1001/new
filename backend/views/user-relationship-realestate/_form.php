<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserRelationshipRealestate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-relationship-realestate-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-lg-4">
			<?= $form->field($model, 'account_id')->textInput(['maxlength' => true])->hint('请填写用户串码') ?>
		</div>
		<div class="col-lg-4">
			<?= $form->field($model, 'realestate_id')->textInput()->hint('请填写房屋编码') ?>
		</div>
	</div>
    

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'New' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
