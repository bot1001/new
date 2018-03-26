<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-data-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'account_id')->textInput(['maxlength' => true,'readonly' => true]) ?>
		</div><div class="col-lg-3">
			<?= $form->field($model, 'real_name')->textInput(['maxlength' => true,'readonly' => true]) ?>
		</div>
	</div>

    <?php // $form->field($model, 'gender')->textInput() ?>

    <?php // $form->field($model, 'face_path')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'province_id')->textInput() ?>

    <?php // $form->field($model, 'city_id')->textInput() ?>

    <?php // $form->field($model, 'area_id')->textInput() ?>

    <?php // $form->field($model, 'reg_time')->textInput() ?>

    <?php // $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <strong>经查询，您关联的房号信息如下：</strong><br  /><hr />

    <?php foreach( $reale as $r):?>
    <?php echo $r ?>
    <?php endforeach; ?>

</div>
