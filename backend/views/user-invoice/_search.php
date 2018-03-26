<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-invoice-search">

    <?php $form = ActiveForm::begin([
	    //'layout' => 'horizontal',
	    //'options' => ['class' => 'form-horizontal'],
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <table width="1500">
  <tbody>
    <tr>
      <td>
         <div class="row">
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'invoice_id') ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'community.community_name')->dropDownList([CommunityBasic::find()
		             -> select(['community_name'])
		             -> orderBy('community_name')
		             -> indexBy('community_name')
		             -> column()],['prompt' => '请选择']) ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'building.building_name')->textInput() ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'room.room_name') ?>
	       	</div>
	       	<div class="col-lg-2">
	       		<?= $form->field($model, 'description') ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'invoice_amount') ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'order_id') ?>
	       	</div>
	       	<div class="col-lg-1">
	       		<?= $form->field($model, 'invoice_status')->dropDownList([0=>'欠费',1=>'银行',2=>'线上',3=>'线下',4=>'优惠',5=>'政府'],['prompt'=>'请选择']) ?>
	       	</div>
	     
	     <div class="form-group col-lg-1">
            <?= Html::submitButton('', ['class' => 'btn btn-info']) ?>
            <?php // Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
         </div>
         </div>
      </td>
    </tr>
  </tbody>
</table>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    

    <?php // echo $form->field($model, 'create_time') ?>

    

    <?php // echo $form->field($model, 'invoice_notes') ?>

    <?php // echo $form->field($model, 'payment_time') ?>

    

    <?php // echo $form->field($model, 'update_time') ?>

    

    <?php ActiveForm::end(); ?>

</div>
