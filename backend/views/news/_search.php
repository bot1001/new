<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'news_id') ?>

    <?= $form->field($model, 'community_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'excerpt') ?>

    <?= $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'post_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'view_total') ?>

    <?php // echo $form->field($model, 'stick_top') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
