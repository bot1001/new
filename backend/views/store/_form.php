<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_cover')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'store_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'community_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_introduce')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_longitude')->textInput() ?>

    <?= $form->field($model, 'store_latitude')->textInput() ?>

    <?= $form->field($model, 'add_time')->textInput() ?>

    <?= $form->field($model, 'is_certificate')->textInput() ?>

    <?= $form->field($model, 'store_sort')->textInput() ?>

    <?= $form->field($model, 'store_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'store_taxonomy')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
