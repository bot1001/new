<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommunityBasic;
use app\models\Company;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-building-form">

    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'company')->dropDownList($company,['prompt' => '请选择', 'id' => 'company']) ?>

    <?= $form->field($model, 'community_id')->widget(DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'options'=>['id'=>'building'],
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
            'depends'=>['company'],
            'placeholder'=>'请选择...',
            'url'=>Url::to(['/company/c'])
        ]
    ]); ?>

    <?= $form->field($model, 'building_name')->textInput(['maxlength' => true, 'placeHolder' => '请输入房号']) ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
