<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdvertisingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertising-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ad_id') ?>

    <?= $form->field($model, 'ad_title') ?>

    <?= $form->field($model, 'ad_excerpt') ?>

    <?= $form->field($model, 'ad_poster') ?>

    <?= $form->field($model, 'ad_publish_community') ?>

    <?php // echo $form->field($model, 'ad_type') ?>

    <?php // echo $form->field($model, 'ad_target_value') ?>

    <?php // echo $form->field($model, 'ad_location') ?>

    <?php // echo $form->field($model, 'ad_created_time') ?>

    <?php // echo $form->field($model, 'ad_sort') ?>

    <?php // echo $form->field($model, 'ad_status') ?>

    <?php // echo $form->field($model, 'property') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
