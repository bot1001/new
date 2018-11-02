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
        border: solid 1px rgba(128, 128, 128, 0.5);
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
    .type{
        display: none;
    }
    .save{
        position: relative;
        bottom: 7px;
    }
</style>

<div class="store-taxonomy-form">
    <div class="main">
        <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL
        ]); ?>

        <div class="type">
            <?php
            if(!$type){
                $type = '0';
            }
            if($type == '0'){
                echo $form->field($model, 'parent')->dropDownList( ['0' => '行业'], ['maxlength' => true]);
            }
            echo $form->field($model, 'type')->textInput(['value' => $type]); ?>
        </div>
        
        <?php
           if($type == '-1' || $type == '-2') {
               echo $form->field($model, 'name')->textInput(['maxlength' => true]);
               echo $form->field($model, 'parent')->dropDownList(\common\models\StoreTaxonomy::Taxonomy($type = $type + 1), ['maxlength' => true, 'prompt' => '请选择']);
           }elseif($type == '0'){
               echo $form->field($model, 'name')->textInput(['maxlength' => true]);
           }
        ?>

        <?= $form->field($model, 'sort')->input('number') ?>

        <?= $form->field($model, 'property')->textInput(['maxlength' => true]) ?>

        <div class="form-group save">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
