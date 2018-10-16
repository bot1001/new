<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\StoreAccount */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .store-account-form{
        max-width: 500px;
        min-height: 300px;
        border: solid 2px rgba(255, 255, 255, 0.8);
        border-radius: 10px;
    }
    .main{
        margin: auto;
        margin-top: 3%;
        width: 95%;
    }
    .form-group{
        text-align: center;
    }
    #storeaccount-user_id, #storeaccount-work_number, #storeaccount-store_id, #storeaccount-role, #storeaccount-status{
        border-radius: 5px;
    }
</style>

<div class="store-account-form">
    <div class="main">
        <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => '3']
        ]); ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'work_number')->textInput() ?>

        <?= $form->field($model, 'store_id')->textInput() ?>

        <?= $form->field($model, 'role')->textInput() ?>

        <?= $form->field($model, 'status')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>



</div>
