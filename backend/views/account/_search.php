<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'user_name') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'mobile_phone') ?>

    <?php // echo $form->field($model, 'qq_openid') ?>

    <?php // echo $form->field($model, 'weixin_openid') ?>

    <?php // echo $form->field($model, 'weibo_openid') ?>

    <?php // echo $form->field($model, 'account_role') ?>

    <?php // echo $form->field($model, 'new_message') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
