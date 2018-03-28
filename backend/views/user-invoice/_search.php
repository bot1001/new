<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

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
<table>
	<tr style="width: 100%">
		<td>
			<div>
	       		<?= $form->field($model, 'community_id')->CheckboxList($comm) ?>
	       	</div>
		</td>
    </tr>
    
    <tr>
		<td>
			<div>
	       		<?= $form->field($model, 'building_id')?>
	       	</div>
		</td>
		
       	<td>
	       	<div>
	       		<?= $form->field($model, 'description') ?>
	       	</div>
	    </td>
       	<td>
	       	<div>
	       		<?= $form->field($model, 'from') ?>
	       	</div>
	    </td>
       	<td>	       	
	       	<div>
	       		<?= $form->field($model, 'invoice_status')->dropDownList([0=>'欠费',1=>'银行',2=>'线上',3=>'线下',4=>'优惠',5=>'政府'],['prompt'=>'请选择']) ?>
	       	</div>
	    </td>
       	<td> 
	        <div class="form-group col-lg-1">
               <?= Html::submitButton('搜索', ['class' => 'btn btn-info']) ?>
            </div>
		</td>
	</tr>
</table>

    <?php ActiveForm::end(); ?>

</div>
