<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .c{
        width: 500px;
        background: #ffffff;
        border-radius:10px;
    }
    #realestate_id{
        display: none;
    }
</style>

<div class="house-info-form" align="center">

    <h1><?= $room ?></h1>

    <?php $form = ActiveForm::begin(); ?>
<table class="c">
    <tr height="15px">
        <td></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td>
            <div class="row">
            	<div class="col-lg-3">
            		<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '姓名'])->label(false) ?>
            	</div>
            	<div class="col-lg-4">
            		<?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeHolder' => '手机号码'])->label(false) ?>
            	</div>
            </div>

            <div class="row">
            	<div class="col-lg-7">
            		<?= $form->field($model, 'IDcard')->textInput(['length' => [15, 20], 'placeHolder' => '请输入身份证号码'])->label(false) ?>
            	</div>
            </div>

            <div class="row">
            	<div class="col-lg-12">
            		<?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeHolder' => '请输入地址'])->label(false) ?>
            	</div>
	        </div>

            <div class="row">
            	<div class="col-lg-12">
            		<?= $form->field($model, 'property')->textInput(['maxlength' => true, 'placeHolder' => '请输入备注'])->label(false) ?>
            	</div>
            </div>
            <div id="realestate_id">
                <?= $form->field($model, 'realestate')->textInput(['value' => $id]) ?>
            </div>

            <div class="form-group" align="center">
                <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
            </div>
        </td>
        <td width="5%"></td>
    </tr>
</table>
    <?php ActiveForm::end(); ?>

</div>
