<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\StoreTaxonomy */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .form-group{
        text-align: center;
    }
    .store-taxonomy-form{
        min-height: 270px;
        width: 500px;
        border: solid 1px rgba(255,255,255, 0.8);
        border-radius: 10px;
    }
    .main{
        height: 95%;
        width: 90%;
        margin: auto;
        margin-top: 3%;
    }
    #storetaxonomy-name, #storetaxonomy-type, #storetaxonomy-sort, #storetaxonomy-property{
        /*background: #000;*/
        border-radius: 5px;
    }
</style>

<div class="store-taxonomy-form">
    <div class="main">
        <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->textInput() ?>

        <?= $form->field($model, 'sort')->textInput() ?>

        <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>



</div>
