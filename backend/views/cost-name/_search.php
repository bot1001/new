<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CostNameSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-name-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cost_id') ?>

    <?= $form->field($model, 'cost_name') ?>

    <?= $form->field($model, 'price') ?>
    
    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'inv') ?>

    <?= $form->field($model, 'property') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
