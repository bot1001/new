<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-relation-form">

	<?php $form = ActiveForm::begin(); ?>
    	<div class="row">
			<div class="col-lg-4">
				<?= $form->field($model, 'community')->dropDownList( \app\models\CommunityBasic::community(), ['prompt'=>'请选择', 'id'=>'community']);?>
			</div>
			<div class="col-lg-3">
				<?= $form->field($model, 'building_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'building'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['community'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/costrelation/b2'])
                    ]
                ]); ?>
			</div>

			<div class="col-lg-3">
				<?= $form->field($model, 'number')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'number'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['building'],
                        'placeholder'=>'...',
                        'url'=>Url::to(['/costrelation/number'])
                    ]
                ]); ?>
			</div>
        </div>

		<?= $form->field($model, 'realestate_id')->widget(DepDrop::classname(), [
           'type' => DepDrop::TYPE_SELECT2,
           'options'=>['id'=>'realestate'],
	       'select2Options'=>['pluginOptions'=>['multiple' => true, 'allowClear'=>true]],
           'pluginOptions'=>[
               'depends'=>['number'],
               'placeholder'=>'请选择...',
               'url'=>Url::to(['/costrelation/re']),
	           'params'=>['building'], //另一个上级目录ID
           ]
        ]); ?>

		<div class="row">
			<div class="col-lg-4">
				<?= $form->field($model, 'price')->dropDownList($cost,['prompt'=>'请选择','id'=>'costrelation-parent']) ?>
			</div>
			<div class="col-lg-3">
				<?= $form->field($model, 'cost_id')->widget(DepDrop::classname(), [
                   'type' => DepDrop::TYPE_SELECT2,
                   'options'=>['id'=>'costrelation-price'],
	               'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                   'pluginOptions'=>[
                       'depends'=>['costrelation-parent'],
                       'placeholder'=>'请选择...',
                       'url'=>Url::to(['/costrelation/p'])
                   ]
               ]); ?>
			</div>
			<div class="col-lg-4">
					<?= $form->field($model, 'status')->dropDownList(['禁用', '启用'],['prompt'=>'请选择']) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<?= $form->field($model, 'from', [
                    'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                    'options'=>['class'=>'drp-container']])
	                         ->widget(DateRangePicker::classname(), [
                    'useWithAddon'=>true,
		        	'pluginOptions'=>[
                    'singleDatePicker'=>true,
                    'showDropdowns'=>true
                    ]
                ]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10">
				<?= $form->field($model, 'property')->textArea(['maxlength' => true]) ?>
			</div>
		</div>

		<div class="form-group" align="center">
			<?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>