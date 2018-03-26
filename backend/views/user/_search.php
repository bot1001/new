<?php

use yii\ helpers\ Html;
use kartik\ form\ ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-basic-search">

	<?php $form = ActiveForm::begin([
	      'type' => ActiveForm::TYPE_INLINE,
          'action' => ['index'],
          'method' => 'get',
      ]); ?>

	<div class="row">
		<div class="col-lg-2">
			<?php
			echo $form->field( $model, 'fromdate' )->widget( \yii\ jui\ DatePicker::classname(), [
				'language' => 'zh-CN',
				'dateFormat' => 'yyyy-MM-dd',
			] );
			?>
		</div>
		<div class="col-lg-2">
			<?php
			echo $form->field( $model, 'todate' )->widget( \yii\ jui\ DatePicker::classname(), [
				'language' => 'zh-CN',
				'dateFormat' => 'yyyy-MM-dd',
			] );
			?>

		</div>
		<div class="col-lg-1">
			<div class="form-group">
				<?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>