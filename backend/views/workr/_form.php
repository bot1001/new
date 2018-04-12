<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkR */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-r-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'work_number')->textInput() ?>

    <?= $form->field($model, 'community_id')->textInput() ?>

    <?= $form->field($model, 'account_superior')->textInput() ?>

    <?= $form->field($model, 'work_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_role')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
