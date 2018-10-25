<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PhoneList;

/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .phone-list-create{
        width: 400px;
        margin: auto;
    }
    .phone-list-form{
        width: 90%;
        border: solid 1px #bee5eb;
        border-radius: 5px;
        background: #fdfdfd;
        margin: auto;
    }
    #lower{
        display: none;
    }
    #number{
        display: inline-flex;
    }
    .n{
        width: 178px;
    }
    .center{
        text-align: center;
    }
    #phonelist-phone_name, #phonelist-phone_number, #phonelist-parent_id, #phonelist-phone_sort{
        border-radius: 5px;
    }
</style>
<div class="phone-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone_name')->textInput(['maxlength' => true, 'placeholder' => '号码名称'])->label(false) ?>

    <?= $form->field($model, 'phone_number')->textInput([ 'placeholder' => '电话号码'])->label(false) ?>


    <?= $form->field($model, 'have_lower')->textInput(['value' => '0', 'id' => 'lower'])->label(false) ?>
    <div id="number">
        <div class="n">
            <?= $form->field($model, 'parent_id')->dropDownList(PhoneList::phone(), ['prompt' => '类型'])->label(false) ?>
        </div>
        <div class="n">
            <?= $form->field($model, 'phone_sort')->input('number', ['placeholder' => '顺序'])->label(false) ?>
        </div>
    </div>

    <div class="form-group center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
