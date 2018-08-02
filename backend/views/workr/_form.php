<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\ helpers\ ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\WorkR */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .work-r-form{
        width: 90%;
        margin: auto
    }
</style>

<div class="work-r-form">

    <div class="work-f">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'work_number')->dropDownList($company,['prompt' => '请选择','id'=>'community'])->label('公司') ?>

        <?= $form->field($model, 'community_id')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'options'=>['id'=>'building'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['community'],
                'placeholder'=>'请选择...',
                'url'=>Url::to(['/company/c'])
            ]
        ]); ?>

        <?= $form->field($model, 'account_id')->dropDownList($user) ?>

        <div class="form-group" align="center">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
