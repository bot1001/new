<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\StoreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'store_cover') ?>

    <?= $form->field($model, 'province_id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?= $form->field($model, 'area_id') ?>

    <?php // echo $form->field($model, 'store_name') ?>

    <?php // echo $form->field($model, 'community_id') ?>

    <?php // echo $form->field($model, 'store_address') ?>

    <?php // echo $form->field($model, 'store_introduce') ?>

    <?php // echo $form->field($model, 'store_phone') ?>

    <?php // echo $form->field($model, 'store_longitude') ?>

    <?php // echo $form->field($model, 'store_latitude') ?>

    <?php // echo $form->field($model, 'add_time') ?>

    <?php // echo $form->field($model, 'is_certificate') ?>

    <?php // echo $form->field($model, 'store_sort') ?>

    <?php // echo $form->field($model, 'store_status') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'store_taxonomy') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
