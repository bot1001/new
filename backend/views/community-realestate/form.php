<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-realestate-form">

  <?php $form = ActiveForm::begin(); ?>
    <table border="0" align="center" style="border-radius: 20px; background-color: #F3F3F3; width: 550">
    	<tbody>
    		  <tr>
		      	<td width="5%"></td>
		      	<td>
		      		<div class="row">
		      			<div class="col-lg-4">
		      				<?= $form->field($model, 'community_id')->dropDownList($comm,['readonly' => true]) ?>
		      			</div>
		      			<div class="col-lg-3">
		      				<?= $form->field($model, 'building_id')->dropDownList($bu,['readonly' => true]) ?>
		      			</div>
		      			<div class="col-lg-2">
		      				<?= $form->field($model, 'room_number')->textInput(['maxlength' => true]) ?>
		      			</div>
		      			<div class="col-lg-3">
		      				<?= $form->field($model, 'room_name')->textInput(['maxlength' => true]) ?>
		      			</div>
		      		</div>
		      		<div class="row">
		      			<div class="col-lg-3">
		      				<?= $form->field($model, 'owners_name')->textInput(['maxlength' => true]) ?>
		      			</div>
		      			<div class="col-lg-4">
		      				<?= $form->field($model, 'owners_cellphone')->textInput(['maxlength' => true]) ?>
		      			</div>
		      		</div>
		      		<div class="row">
		      			<div class="col-lg-3">
		      				<?= $form->field($model, 'acreage')->textInput(['maxlength' => true]) ?>
		      			</div>
		      		</div>
             		<div class="form-group" align="center">
		      			<?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		      		</div>
		      	</td>
		      	<td width="4%"></td>
		      </tr>
    	</tbody>
    </table>
  <?php ActiveForm::end(); ?>

</div>