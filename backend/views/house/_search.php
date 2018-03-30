<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\houseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="house-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'house_id') ?>

    <?= $form->field($model, 'realestate') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'IDcard') ?>

    <?php // echo $form->field($model, 'creater') ?>

    <?php // echo $form->field($model, 'create') ?>

    <?php // echo $form->field($model, 'update') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'politics') ?>

    <?php // echo $form->field($model, 'property') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
