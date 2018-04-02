<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestate */

$this->params[ 'breadcrumbs' ][] = [ 'label' => '房屋列表', 'url' => [ 'index' ] ];
?>
<div class="community-realestate-view">

	<?php
	$form = ActiveForm::begin( [ 'options' => [ 'enctype' => 'multipart/form-data' ],
		'type' => ActiveForm::TYPE_INLINE,
		'action' => '/community-realestate/read'
	] )
	?>
	<table style="width:340px; background-color:#F3F3F3;border-radius:20px;" align="center" border="0" height="150" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td scope="col" width="10%">
			</td>
			<td scope="col" colspan="3">
				<?= $form->field($model, 'file')->fileInput() ?>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td colspan="3">
				<?= Html::submitButton('提交', ['class' => 'btn btn-info']) ?>
			</td>
		</tr>
		<tr>
			<td align="right" width="50%" colspan="2">
			</td>
			<td align="center" style="background-color: beige; border-radius:20px;">
				<a href="<?=Url::to(['download']) ?>">
					<font face="arial" color="red">点击下载模板</a>
			</td>
			<td align="right" width="20%">

			</td>
		</tr>
	</tbody>
</table>
	<?php ActiveForm::end() ?>
</div>