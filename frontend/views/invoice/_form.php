<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>
    <style>
		#cost{
			width: 500px;
			text-align:center;
		}
		
		thead tr td{
			font-weight: 700;
		}
		
		tr td{
			height: 30px;
		}
		
		#right{
			text-align: right;
		}
	</style>

<div class="invoice-form">

   <br/>
    <?php $form = ActiveForm::begin(); ?>
	<div class="col-lg-6">
		<?= $form->field($model, 'year', [
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
            ])->textInput(['placeHolder' => '请输入预交起始月份'])->label(false) ?>
	</div>
        
    <div class="col-lg-6">
    	<?= $form->field($model, 'month')->Input('number', ['maxlength' => true, 'placeholder' => '请输入预交月数'])->label(false) ?>
    </div>   
    
    <div class="form-group" align="center">
        <?= Html::submitButton('确定', ['class' => 'btn btn-success', 'disable']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
	<div>
		<p>说明：您只能预交房屋固定费用，具体如下：
		
		<table id="cost" border="1">
			<thead>
				<tr>
					<td>序号</td>
					<td>预交费项</td>
					<td>单价</td>
					<td>备注</td>
				</tr>
			</thead>
		    <?php $i = 0; foreach($cost as $name): $name = (object)$name ?>
			<tr>
				<td><?php $i++; echo $i; ?></td>
				<td><?= $name->cost ?></td>
				<td><?php if($name->cost == "物业费"){
                    	echo $name->price.'元/平米/月';
                    }else{
                    	echo $name->price.'/月';
                    } ?>
                </td>
				<td><?= $name->property ?></td>
			</tr>
			<?php endforeach ?>
		</table>
		</p>
	</div>
</div>
