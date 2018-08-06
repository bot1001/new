<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<style type="text/css">
	#div2{
		margin-top: 25px;
		line-height:25px;
	}
</style>

<div class="user-invoice-search">

	<?php $form = ActiveForm::begin([
	//'type' => ActiveForm::TYPE_INLINE,
	    //'layout' => 'horizontal',
	    //'options' => ['class' => 'form-horizontal'],
        'method' => 'get',
    ]); ?>
    
    <?php 
	$status = [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ];
	?>
    
	<div>
    <div class="row">
    	<div class="col-lg-2">
    		<?= $form->field($model, 'community_id')->dropDownList($comm, ['multiple'=>'multiple'])->label(false) ?>
    	</div>
    	<div class="col-lg-1">
    		<?= $form->field($model, 'building_id')->dropDownList($building, ['multiple'=>'multiple'])->label(false) ?>
    	</div>
    	<div class="col-lg-2">
    		<?= $form->field($model, 'description')->dropDownList($c_name, ['multiple'=>'multiple'])->label(false) ?>
    	</div>
    
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
                                                   ])->textInput(['placeHolder' => '应收区间'])->label(false);
                                               ?>
		
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
                                                   ])->textInput(['placeHolder' => '支付时间'])->label(false);
                                               ?>
		</div>
   	    <div class="col-lg-1">
			<?= $form->field($model, 'invoice_status')->dropDownList($status, ['multiple'=>'multiple'],['prompt'=>'请选择'])->label(false) ?>
	    </div>
	    
	    <div id='div2' class="col-lg-1">
	    	<div class="form-groups">
	    		<?= Html::submitButton('搜索', ['class' => 'btn btn-info']) ?>
				<a href="<?php echo Url::to(['/user-invoice/sum']) ?>" class="glyphicon glyphicon-repeat btn btn-default"></a>
	    	</div>
	    </div>	
	</div>				
    
			
		</div>
	<?php ActiveForm::end(); ?>

</div>