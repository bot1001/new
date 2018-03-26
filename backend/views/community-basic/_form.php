<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-basic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'community_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'community_logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'community_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'community_longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'community_latitude')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
