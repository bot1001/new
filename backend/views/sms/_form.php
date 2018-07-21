<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sign_name')->textInput(['maxlength' => true, 'placeholder' => '模板名称'])->label(false) ?>

    <?= $form->field($model, 'sms')->textInput(['maxlength' => true, 'placeholder' => '模板编号'])->label(false) ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'count')->input('number', ['placeholder' => '变量总数'])->label(false) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'status')->dropDownList([ 0 => '否', 1 => '是'], ['placeholder' => '状态'])->label(false) ?>
        </div>
    </div>

    <?= $form->field($model, 'property')->textarea(['maxlength' => true, 'placeholder' => '备注'])->label(false) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
