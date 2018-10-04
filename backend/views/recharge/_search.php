<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\RechargeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recharge-search">

    <style>
        .row{
            display: flex;
            margin-left: 0px;
        }
        .community{
            width: 220px;
        }
        .building, .number{
            width: 110px;
        }
        .room{
            width: 120px;
        }
    </style>

    <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL]
//        'action' => ['index'],
//        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="community">
            <?= $form->field($model, 'name')->dropDownList(\app\models\CommunityBasic::community(), ['prompt'=>'请选择小区', 'maxlength' => true, 'id' => 'community' ])->label(false) ?>
        </div>

        <div class="building">
            <?= $form->field($model, 'price')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'building'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['community'],
                    'placeholder'=>'楼宇',
                    'url'=>Url::to(['/costrelation/b2'])
                ]
            ])->label(false); ?>
        </div>

        <div class="number">
            <?= $form->field($model, 'type')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'number'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['building'],
                    'placeholder'=>'单元',
                    'url'=>Url::to(['/costrelation/number'])
                ]
            ])->label(false); ?>
        </div>

        <div class="room">
            <?= $form->field($model, 'property')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'room'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['number'],
                    'placeholder'=>'房号',
                    'url'=>Url::to(['/costrelation/re']),
                    'params'=>['building'], //另一个上级目录ID
                ]
            ])->label(false); ?>
        </div>

        <div>
            <?= Html::a('<span class="glyphicon glyphicon-new-window"></span>', '#', ['class' => 'btn btn-default', 'onClick' => 'pay()']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
