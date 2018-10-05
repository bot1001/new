<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Recharge */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .recharge-form{
        width: 90%;
        border: solid 1px rgba(10, 10, 10, 0.05);
        border-radius: 5px;
        margin: auto;
    }
    .form-group{
        text-align: center;
    }
</style>
<div class="recharge-form">

    <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div style="display: none">
        <?= $form->field($model, 'price')->textInput(['value' => '0', 'maxlength' => true]) ?>
        <?= $form->field($model, 'create_time')->textInput() ?>
        <?= $form->field($model, 'creater')->textInput(['maxlength' => true]) ?>
    </div>

    <?= $form->field($model, 'type')->dropDownList(['1' => '电费']) ?>

    <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span>', ['class' => 'btn btn-default']) ?>
    </div>
    <br>
    <?php ActiveForm::end(); ?>

</div>
