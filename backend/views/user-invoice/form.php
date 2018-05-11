<?php

use yii\ helpers\ Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-invoice-form">

	<?php $form = ActiveForm::begin([
	//'options' => ['class'=>'form-horizontal'],
	//'layout' => 'horizontal',
	'action' => ['v'],
    'method' => 'get',
]); ?>

<table align="center" style="width:500px; background-color:#F3F3F3;border-radius:20px;" border="0">
	<tr>

		<td>	
		</td>
	</tr>
</table>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'community_id')->dropDownList($comm) ?>
		</div>
		
		<div class="col-lg-2">
			<?= $form->field($model, 'building_id')->dropDownList($build) ?>
		</div>
		
		<div class="col-lg-2">
			<?= $form->field($model, 'year')->dropDownList($number)->label('单元') ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($model, 'realestate_id')->dropDownList($name) ?>
		</div>
	</div>
			
	<div class="row">
		<div class="col-lg-5">
			<?= $form->field($model, 'from', [
                'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                'options'=>['class'=>'drp-container']])
	                     ->widget(DateRangePicker::classname(), [
                'useWithAddon'=>true,
				'pluginOptions'=>[
                    'singleDatePicker'=>true,
                    'showDropdowns'=>true,
					'locale'=>[
                        'format'=>'Y-M',
                        'separator'=>' to ',
                    ],
                ]
            ])->textInput(['placeHolder' => '请输入预交起始月份'])->label('起始日期'); ?>
		</div>
		
		<div class="col-lg-3">
			<?= $form->field($model, 'month')->input('number',['placeHolder' => '预交月数'])->label('月数'); ?>
		</div>
	</div>		
			
			
			<?= $form->field($model, 'cost')->checkBoxList($cost) ?>
			<div class="form-group" align="center">
				<?= Html::submitButton('确定' , ['class' =>  'btn btn-success']) ?>
			</div>
	
	<?php ActiveForm::end(); ?>

</div>