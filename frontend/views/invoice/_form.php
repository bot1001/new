<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>
    <style>
		#cost{
			width: 300px;
		}
		#center{
			text-align:center;
		}
		
		#right{
			text-align: right;
		}
	</style>

<div class="invoice-form col-lg-5">

   <br/>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'month')->Input('number', ['maxlength' => true, 'placeholder' => '请输入预交月数'])->label(false) ?>

	<div>
		<p>备注：只能预交房屋固定费用，具体如下：
		
		<table id="cost" border="1">
			<thead>
				<tr>
					<td id="center">序号</td>
					<td id="center">费项名称</td>
					<td id="center">单价</td>
					<td id="center">备注</td>
				</tr>
			</thead>
		    <?php $i = 0; foreach($cost as $name): $name = (object)$name ?>
			<tr>
				<td id='center'><?php $i++; echo $i; ?></td>
				<td id='center'><?= $name->cost ?></td>
				<td id='center'><?php if($name->cost == "物业费"){
                    	echo $name->price.'平米/月';
                    }else{
                    	echo $name->price.'/月';
                    } ?>
                </td>
				<td id="center"><?= $name->property ?></td>
			</tr>
			<?php endforeach ?>
		</table>
		</p>
	</div>

    <div class="form-group" align="center">
        <?= Html::submitButton('确定', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
