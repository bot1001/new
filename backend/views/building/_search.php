<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BuildingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-building-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'building_id') ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'building_name') ?>

    <?= $form->field($model, 'building_parent') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
