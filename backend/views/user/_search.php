<?php

use yii\ helpers\ Html;
use kartik\ form\ ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-basic-search">

	<?php $form = ActiveForm::begin([
//	      'type' => ActiveForm::TYPE_INLINE,
          'action' => ['sum'],
          'method' => 'get',
      ]); ?>

	<div class="row">
        <div class="col-lg-2">
            <?= $form->field( $model, 'company' )->dropDownList(\common\models\Company::Company())->label(false);?>
        </div>

        <div class="col-lg-1">
            <?= $form->field( $model, 'com' )->label(false);?>
        </div>
        <div class="col-lg-2">
            <?= $form->field( $model, 'community' )->label(false);?>
        </div>

        <div class="col-lg-1">
            <?= $form->field( $model, 'number' )->label(false);?>
        </div>

		<div class="col-lg-2">
			<?= $form->field( $model, 'fromdate' )->label(false);
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