<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SysUser */
/* @var $form yii\widgets\ActiveForm */

$this->title = '修改密码';
?>

<style type="text/css">
	div{
		margin: auto
	}
</style>

<h3><center>修改密码</center></h3>
<br>

<div class="sys-user-form div">

	<?php $form = ActiveForm::begin([
	'action' => 'p',
	'layout' => 'horizontal',
	//'type' => ActiveForm::TYPE_INLINE,
	'options' => ['class' => 'form-horizontal'],
]); ?>

    <?php $message = Yii::$app->getSession()->getFlash('fail');
	    if($message == 1){
	    	echo "<script>alert('原密码错误，请重新输入！')</script>";
	    }
	?>

<table style="width:340px; background-color:#F3F3F3;border-radius:20px;" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<br>
		</td>
	</tr>
	<tr>
		<td align="center">
			<div>
				<?= $form->field($model, 'password',['template' => '{label} <div class="row"><div class="col-sm-7">{input}{error}{hint}</div></div>'])->passwordInput(['maxlength' => true,'placeHolder' =>'请输入原密码'])->label('原密码') ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
			<div>
				<?= $form->field($model, 'new_pd',['template' => '{label} <div class="row"><div class="col-sm-7">{input}{error}{hint}</div></div>'])->passwordInput(['maxlength' => true,'placeHolder' =>'请输入新密码'])->label('新密码') ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
			<div>
				<?= $form->field($model, 'n',['template' => '{label} <div class="row"><div class="col-sm-7">{input}{error}{hint}</div></div>'])->passwordInput(['maxlength' => true,'placeHolder' =>'请重新输入新密码'])->label('新密码') ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
			<div>
				<?= Html::submitButton('确定', ['class' => 'btn btn-success']) ?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<br>
		</td>
	</tr>
</table>

	<?php ActiveForm::end(); ?>

</div>
