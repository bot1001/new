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
	//'type' => ActiveForm::TYPE_INLINE,
	    //'layout' => 'horizontal',
	    //'options' => ['class' => 'form-horizontal'],
        'method' => 'get',
    ]); ?>
    <div class="row">
    	<div class="col-lg-3">
    		<?= $form->field($model, 'community_id')->dropDownList($comm, ['multiple'=>'multiple']) ?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'building_id')?>
    	</div>
    	<div class="col-lg-3">
    		<?= $form->field($model, 'description')->dropDownList($c_name, ['multiple'=>'multiple']) ?>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-lg-2">
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
		</div>
   	
   	    <div class="col-lg-2">
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
		</div>
   	    <div class="col-lg-2">
			<?= $form->field($model, 'invoice_status')->dropDownList([ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ],['prompt'=>'请选择']) ?>
		</div>
	
		<div class="col-lg-1">
			<div class="form-groups" style="bottom: 0px">
				<?= Html::submitButton('搜索', ['class' => 'btn btn-info']) ?>
			</div>
		</div>
	</div>				

	<?php ActiveForm::end(); ?>

</div>