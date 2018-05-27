<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertising-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ad_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ad_excerpt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ad_poster')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ad_publish_community')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ad_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ad_target_value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ad_location')->textInput() ?>

    <?= $form->field($model, 'ad_created_time')->textInput() ?>

    <?= $form->field($model, 'ad_sort')->textInput() ?>

    <?= $form->field($model, 'ad_status')->textInput() ?>

    <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
