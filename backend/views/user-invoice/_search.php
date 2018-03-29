<?php

use yii\ helpers\ Html;
use kartik\ form\ ActiveForm;
use kartik\ daterange\ DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-invoice-search">

	<?php $form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_INLINE,
	    //'layout' => 'horizontal',
	    //'options' => ['class' => 'form-horizontal'],
        //'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<table style="width: 100%">
		<tr>
			<td colspan="7">
				<?= $form->field($model, 'community_id')->CheckboxList($comm) ?>
			</td>
		</tr>

		<tr>
			<td colspan="7">
				<?= $form->field($model, 'building_id')?>
			</td>
		</tr>
		<tr>

			<td width="50%">
				<?= $form->field($model, 'description')->CheckboxList($c_name) ?>
			</td>

			<td>
				<?= $form->field($model, 'from',['addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                                                 'options'=>['class'=>'drp-container form-group']])
	                                             ->widget(DateRangePicker::classname(), [
												    'useWithAddon'=>true,
	                                                  'pluginOptions'=>[
                                                          'locale'=>[
		                                                      'format'=>'Y-M',
		                                                      'separator'=>' to ',
	                                                      ],
                                                        //'singleDatePicker'=>true,
                                                        'showDropdowns'=>true,
                                                        ]
                                                   ]);
                                               ?>
			</td>
			
			<td>
				<?= $form->field($model, 'payment_time',['addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                                                 'options'=>['class'=>'drp-container form-group']])
	                                             ->widget(DateRangePicker::classname(), [
												    'useWithAddon'=>true,
	                                                  'pluginOptions'=>[
                                                          'locale'=>[
		                                                      //'format'=>'Y-M',
		                                                      'separator'=>' to ',
	                                                      ],
                                                        //'singleDatePicker'=>true,
                                                        'showDropdowns'=>true,
                                                        ]
                                                   ]);
                                               ?>
			</td>
			<td>
				<?= $form->field($model, 'invoice_status')->dropDownList([ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ],['prompt'=>'请选择']) ?>
			</td>
			<td>
				<div class="form-groups">
					<?= Html::submitButton('搜索', ['class' => 'btn btn-info']) ?>
				</div>
			</td>
		</tr>
	</table>

	<?php ActiveForm::end(); ?>

</div>