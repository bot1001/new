<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Instructions */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .instructions-form{
        max-width: 800px;
    }
    .center{
        text-align: center;
    }
</style>

<div class="instructions-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => '标题'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'content')->widget('kucha\ueditor\UEditor',
                ['clientOptions' =>
                    ['initialFrameHeight' => '300', 'toolbars' =>
                        [
                            ['fullscreen', 'undo', 'redo', 'bold','imageScaleEnabled', 'autoClearEmptyNode', 'fontfamily',
                                'snapscreen', 'link', 'unlink', 'simpleupload', 'insertImage', 'fontfamily', 'customstyle',
                                'paragraph', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', 'imagenone', 'imageleft',
                                'imageright', 'imagecenter', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify',
                                'rowspacingtop', 'rowspacingbottom', 'lineheight']
                        ]
                    ]
                ]
            )->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2">
            <?= $form->field($model, 'type')->dropDownList(\common\models\Instructions::arr($one = 'type'), ['prompt' => '平台'])->label(false) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'sort')->input('number', ['placeholder' => '排序'])->label(false) ?>
        </div>

        <div class="col-lg-2">
            <?= $form->field($model, 'status')->dropDownList(\common\models\Instructions::arr($one = 'status'), ['prompt' => '状态'])->label(false) ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'version')->textInput(['maxlength' => true, 'placeholder' => '版本号, 如：Version：1.0.0'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'property')->textarea(['maxlength' => true, 'placeholder' => '备注'])->label(false) ?>
        </div>
    </div>

    <div class="form-group center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
