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
    .store-form{
        width: 800px;
        border: solid 1px black;
        border-radius: 5px;
        background: whitesmoke;
    }
    .main{
        max-width: 800px;
    }
    .disabled{
        display: none;
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

        <div class="row">
            <div class="col-lg-2">
                <?= $form->field($model, 'person')->textInput(['maxlength' => true])->label(false) ?>
                <?= $form->field($model, 'store_phone')->textInput(['maxlength' => true])->label(false) ?>
            </div>

            <div class="col-lg-5">
                <?= $form->field($model, 'store_cover')->widget('common\widgets\upload\FileUpload')->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'province_id')->dropDownList(\common\models\Area::getProvince(), ['prompt' => '请选择', 'id' => 'province'])->label(false) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'city_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'city'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['province'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/area/city'])
                    ]
                ])->label(false); ?>

                <?= $form->field($model, 'area_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'area'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['city'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/area/city'])
                    ]
                ])->label(false); ?>
            </div>

            <div class="address col-lg-8">
                <?= $form->field($model, 'store_address')->textarea(['maxlength' => true, 'placeholder' => '请输入详细地址'])->label(false) ?>
            </div>
        </div>

        <?= $form->field($model, 'store_introduce')->widget('kucha\ueditor\UEditor',
            ['clientOptions' =>
                ['initialFrameHeight' => '300', 'toolbars' =>
                    [['fullscreen', 'undo', 'redo', 'bold','imageScaleEnabled', 'autoClearEmptyNode', 'fontfamily',
                        'snapscreen', 'link', 'unlink', 'simpleupload', 'insertImage', 'fontfamily', 'customstyle',
                        'paragraph', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                        'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', 'imagenone', 'imageleft',
                        'imageright', 'imagecenter', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify',
                        'rowspacingtop', 'rowspacingbottom', 'lineheight']
                    ]
                ]
            ]
        )->label(false) ?>

        <div class="disabled">
            <?= $form->field($model, 'add_time')->textInput() ?>

            <?= $form->field($model, 'is_certificate')->textInput() ?>

            <?= $form->field($model, 'store_sort')->textInput() ?>

            <?= $form->field($model, 'store_status')->textInput(['maxlength' => true, 'value' => '2']) ?>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'store_taxonomy')->dropDownList(\common\models\StoreTaxonomy::Taxonomy($type = '1'))->label(false) ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'type')->dropDownList([1=> '股份制', 2 => '个体经营 '])->label(false) ?>
            </div>
        </div>

        <div class="form-group submit">
            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
