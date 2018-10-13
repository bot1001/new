<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .product-form{
        min-width: 700px;
        max-width: 900px;
        border: solid #00ca6d 1px;
        border-radius: 10px;
    }
    #title{
        display: flex;
    }
    .title01{
        width: 20%;
    }
    .title02{
        width: 20px;
    }
    .title03{
        width: 30%;
    }
    #price{
        display: flex;
    }
    .price{
        /*width: 30%;*/
        /*text-align: center;*/
    }
    .center{
        text-align: center;
    }
    #end{
        display: flex;
    }
</style>

<div class="product-form">

    <?php $form = ActiveForm::begin([
//            'type' => ActiveForm::TYPE_HORIZONTAL,
//        'formConfig' => ['labelSpan' => '3']
    ]); ?>

    <?= $form->field($model, 'store_id')->dropDownList(\common\models\Store::getStore()) ?>

    <div id="title">
        <div class="title01"><?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?></div>
        <div class="title02"></div>
        <div class="title03"><?= $form->field($model, 'product_subhead')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div id="price">
        <div class="price"><?= $form->field($model, 'brand_id')->textInput(['maxlength' => true]) ?></div>
        <div class="title02"></div>
        <div class="price"><?= $form->field($model, 'product_taxonomy')->textInput(['maxlength' => true]) ?></div>
        <div class="title02"></div>
        <div class="price"><?= $form->field($model, 'product_image')->widget('common\widgets\upload\FileUpload')->label(false) ?></div>
    </div>


    <?= $form->field($model, 'product_introduction')->widget('kucha\ueditor\UEditor',['clientOptions' =>['initialFrameHeight' => '300', 'config' => '']])->label(false) ?>

    <div id="end">
        <?= $form->field($model, 'product_quantity')->input('number') ?>
        <div><?= $form->field($model, 'market_price')->textInput(['maxlength' => true]) ?></div>
        <div><?= $form->field($model, 'product_price')->textInput(['maxlength' => true]) ?></div>
        <div style="display: none">
            <?= $form->field($model, 'product_status')->textInput(['value' => '3']) ?>
        </div>
    </div>

    <div class="form-group center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
