<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .main{
        max-width: 800px;
    }
    .disabled{
        display: none;
    }
    .area{
        display: flex;
    }
    .submit{
        text-align: center;
    }
</style>

<div class="store-form">
    <div class="main">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'store_name')->textInput(['maxlength' => true, 'placeHolder' => '请输入店面名称'])->label(false) ?>
            </div>
        </div>

        <?= $form->field($model, 'store_phone')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-lg-6">
                <div class="area">
                    <?= $form->field($model, 'province_id')->dropDownList(\common\models\Area::getProvince(), ['prompt' => '请选择', 'id' => 'province']) ?>

                    <?= $form->field($model, 'city_id')->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'city'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['province'],
                            'placeholder'=>'请选择...',
                            'url'=>Url::to(['/area/city'])
                        ]
                    ]); ?>

                    <?= $form->field($model, 'area_id')->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'area'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['city'],
                            'placeholder'=>'请选择...',
                            'url'=>Url::to(['/area/city'])
                        ]
                    ]); ?>
                </div>

                <div class="address">
                    <?= $form->field($model, 'store_address')->textInput(['maxlength' => true, 'placeholder' => '请输入详细地址'])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'store_cover')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?= $form->field($model, 'community_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'store_introduce')->widget('kucha\ueditor\UEditor',['clientOptions' =>['initialFrameHeight' => '300', 'config' => '']])->label(false) ?>

        <div class="disabled">
            <?= $form->field($model, 'store_longitude')->textInput() ?>

            <?= $form->field($model, 'store_latitude')->textInput() ?>

            <?= $form->field($model, 'add_time')->textInput(['value' => time()]) ?>

            <?= $form->field($model, 'is_certificate')->textInput(['value' => '0']) ?>

            <?= $form->field($model, 'store_sort')->textInput(['value' => '0']) ?>

            <?= $form->field($model, 'store_status')->textInput(['maxlength' => true, 'value' => '2']) ?>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'store_taxonomy')->dropDownList(\common\models\StoreTaxonomy::Taxonomy($type = '1')) ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'type')->dropDownList([1=> '超市', 2 => '商店']) ?>
            </div>
        </div>

        <div class="form-group submit">
            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
