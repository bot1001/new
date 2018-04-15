<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-community-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sys_user_id')->textInput() ?>

    <?= $form->field($model, 'community_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'own_add')->textInput() ?>

    <?= $form->field($model, 'own_delete')->textInput() ?>

    <?= $form->field($model, 'own_update')->textInput() ?>

    <?= $form->field($model, 'own_select')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
