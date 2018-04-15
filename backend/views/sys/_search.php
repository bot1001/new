<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-community-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sys_user_id') ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'own_add') ?>

    <?= $form->field($model, 'own_delete') ?>

    <?php // echo $form->field($model, 'own_update') ?>

    <?php // echo $form->field($model, 'own_select') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
