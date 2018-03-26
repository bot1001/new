<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserRelationshipRealestate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-relationship-realestate-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'account_id')->textInput(['maxlength' => true,'readonly' => true]) ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'realestate_id')->textInput() ?>
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php foreach($realestate as $r): ?>
    <strong>经查询，您要绑定的房号为：</strong>
    <?php echo $r->realestate_id?>
    <?php //echo $r->room_name; ?>
    <?php endforeach; ?>

</div>