<?php

use yii\ helpers\ Html;
use kartik\ form\ ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WaterSearch01 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="water-meter-search">

	<?php $form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_INLINE,
        'action' => ['new'],
        'method' => 'get',
    ]); ?>

	<div class="row">
		<div class="col-lg-2">
			<?= $form->field($model, 'community') ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'building') ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'name') ?>
		</div>
		<div class="col-lg-1">
			<?= $form->field($model, 'year') ?>
		</div>

		<div class="col-lg-1">
			<?= $form->field($model, 'month') ?>
		</div>
		<div class="col-lg-2">
			<?= $form->field($model, 'readout') ?>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<?= Html::submitButton('Going', ['class' => 'btn btn-info']) ?>
			</div>
		</div>


	</div>

	<?php // echo $form->field($model, 'property') ?>



	<?php ActiveForm::end(); ?>

</div>