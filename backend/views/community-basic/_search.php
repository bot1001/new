<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-basic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'community_name') ?>

    <?= $form->field($model, 'community_logo') ?>

    <?= $form->field($model, 'province_id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'area_id') ?>

    <?php // echo $form->field($model, 'community_address') ?>

    <?php // echo $form->field($model, 'community_longitude') ?>

    <?php // echo $form->field($model, 'community_latitude') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
