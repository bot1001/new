<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InformationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="information-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'remind_id') ?>
    
    <?= $form->field($model, 'detail') ?>

    <?= $form->field($model, 'times') ?>

    <?= $form->field($model, 'reading') ?>

    <?php // echo $form->field($model, 'target') ?>

    <?php // echo $form->field($model, 'ticket_number') ?>

    <?php // echo $form->field($model, 'remind_time') ?>

    <?php // echo $form->field($model, 'property') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
