<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use kartik\ form\ ActiveForm;
use app\models\CommunityBasic;
use kartik\ depdrop\ DepDrop;
use kartik\ select2\ Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestatenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-realestate-search">

	<?php
	$c = $_SESSION[ 'community' ];

	$comm = CommunityBasic::find()
		->select('community_name, community_id')
		->where(['in', 'community_id', $c])
		->orderBy('community_name')
		->indexBy('community_id')
		->column();
	?>
	<?php $form = ActiveForm::begin([
	    'type' => ActiveForm::TYPE_INLINE,
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<?php // $form->field($model, 'realestate_id') ?>

	<div class="row">
		<div class="col-lg-2">
			<?= $form->field($model, 'community_id')->dropDownList($comm,['prompt' => '请选择','id'=>'community']) ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'building_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'building'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['community'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/b'])
                ]
            ]); ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'room_number')->textInput(['readonly' => true]) ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'room_name')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'reale'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['building'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/r'])
                ]
            ]); ?>
		</div>
		<div class="col-lg-2">
			<?= $form->field($model,'owners_name')->textInput(['placeholder'=>'业主姓名'])->label('姓名') ?>
		</div>
		<div class="col-lg-1">
			<?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>