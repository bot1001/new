<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use kartik\ form\ ActiveForm;
use yii\ helpers\ ArrayHelper;
use kartik\ depdrop\ DepDrop;
use kartik\ select2\ Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestatenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-realestate-search">

	<?php
	$c = $_SESSION[ 'user' ][ 'community' ];
	if ( $c ) {
		$array1 = Yii::$app->db->createCommand( "select community_id,community_name from community_basic where community_id = '$c'" )->queryAll();
	} else {
		$array1 = Yii::$app->db->createCommand( 'select community_id,community_name from community_basic' )->queryAll();
	}

	$comm = ArrayHelper::map( $array1, 'community_id', 'community_name' );
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