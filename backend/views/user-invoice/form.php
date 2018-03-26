<?php

use yii\ helpers\ Html;
use yii\ bootstrap\ ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-invoice-form">

	<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal'],
	//'layout' => 'horizontal',
	'action' => ['v','id' => $id],
    'method' => 'get',
]); ?>

<table align="center" style="width:500px; background-color:#F3F3F3;border-radius:20px;" border="0">
	<tr>
		<td width="8%"></td>
		<td>
			<?= $form->field($model, 'year')->inline()->radioList($y) ?>
		</td>
	</tr>
	<tr>
		<td width="8%"></td>
		<td>
			<div class="row">
				<div class="col-lg-10">
					<?= $form->field($model, 'month')->inline()->checkBoxList($m) ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td width="8%">	</td>
		<td>
			<?= $form->field($model, 'cost')->inline()->checkBoxList($cost) ?>
		</td>
	</tr>
	<tr>
		<td width="8%"></td>
		<td>
			<div class="form-group" align="center">
				<?= Html::submitButton('确定' , ['class' =>  'btn btn-success']) ?>
			</div>
		</td>
	</tr>
</table>
	<?php ActiveForm::end(); ?>

</div>