<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
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

    .center{
        text-align: center;
    }
    #title{
        display: flex;
    }
    .title{
        margin-left: -3%;
    }
    .per_upload_img{
        border-radius: 10px;
    }
    #product-product_name, #product-product_subhead, #product-brand_id, #product-product_taxonomy, #product-product_price,
    #product-product_quantity, #product-market_price, #brand, #product-product_sale, #product-product_status{
        border-radius: 5px;
    }
    .main{
        width: 96%;
        margin: auto;
    }
</style>

<div class="product-form">

    <br />
    <div class="main">
        <?php $form = ActiveForm::begin(); ?>

        <div id="title">
            <div class="col-lg-6 title">
                <div class="col-lg-8"><?= $form->field($model, 'product_name')->textInput(['maxlength' => true, 'placeHolder' => '标题'])->label(false) ?></div>
                <div class="col-lg-12"><?= $form->field($model, 'product_subhead')->textInput(['maxlength' => true, 'placeHolder' => '副标题'])->label(false) ?></div>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-4">
                <div class="col-lg-3"><?= $form->field($model, 'product_image')->widget('common\widgets\upload\FileUpload')->label(false) ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'product_introduction')->widget('kucha\ueditor\UEditor',
                    ['clientOptions' =>['initialFrameHeight' => '300', 'toolbars' => [
                        ['fullscreen', 'undo', 'redo', 'bold','imageScaleEnabled', 'autoClearEmptyNode', 'fontfamily', 'snapscreen', 'link', 'unlink', 'simpleupload', 'insertImage']
                    ]]
                    ]
                )->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4"><?= $form->field($model, 'brand_id')->dropDownList(\common\models\StoreTaxonomy::Taxonomy($type = '2'),['maxlength' => true, 'prompt' => '品牌', 'id' => 'brand'])->label(false) ?></div>
            <div class="col-lg-3">
                <?= $form->field($model, 'product_taxonomy')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'number'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['brand'],
                        'placeholder'=>'系列',
                        'url'=>Url::to(['/taxonomy/tax'])
                    ]
                ])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-2"><?= $form->field($model, 'market_price')->textInput(['maxlength' => true, 'placeHolder' => '市场价格'])->label(false) ?></div>
            <div class="col-lg-2"><?= $form->field($model, 'product_sale')->textInput(['maxlength' => true, 'placeHolder' => '优惠券低现'])->label(false) ?></div>
            <div class="col-lg-2"><?= $form->field($model, 'product_status')->textInput(['maxlength' => true, 'placeHolder' => '积分低现'])->label(false) ?></div>
        </div>

        <div style="display: none">
            <?= $form->field($model, 'store_id')->textInput(['value' => reset($_SESSION['community'])]) ?>
            <?= $form->field($model, 'product_status')->textInput(['value' => '3']) ?>
        </div>
        <div class="form-group center">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
