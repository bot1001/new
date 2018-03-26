<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-relation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'building_id') ?>

    <?= $form->field($model, 'room_number') ?>

    <?= $form->field($model, 'realestate_id') ?>

    <?php // echo $form->field($model, 'cost_id') ?>

    <?php // echo $form->field($model, 'from') ?>

    <?php // echo $form->field($model, 'property') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
