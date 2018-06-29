<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CommunityBasic;
use app\models\CostName;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CostName */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-name-form">
    
    <?php 
	   $Array1 = Yii::$app->db->createCommand('select cost_id, cost_name from cost_name where level =0')->queryAll();
	   $comm = ArrayHelper::map($Array1,'cost_name','cost_name');
	
	   $cost_name = CostName::find()->select('cost_name,cost_id')->where(['level' => 0])->asArray()->all();
	   $cost = ArrayHelper::map($cost_name,'cost_id','cost_name');
	   $c = array_merge(['0' => '父级'],$cost);
	?>

    <?php $form = ActiveForm::begin(); ?>
<table border="0" align="center" style="border-radius: 20px; background-color: #F3F3F3;">
	<tbody>
		<tr>
			<td>

			</td>
			<tr>
				<td width="5%"></td>
				<td>
					<div class="row">
						<div class="col-lg-4">
							<?php
							
			                if ( $role == 7 && (empty($model['cost_name']) || $model['level'] == 0) ) {
			                	//父级费项编辑框  财务经理专用
			                	echo $form->field( $model, 'cost_name' )->textInput();
			                }elseif($role == 14 && empty($model['cost_name'])){
								//创建子级费项，财务专用
								echo $form->field( $model, 'cost_name' )->dropDownList( $comm );
							}elseif($role != 7 && $model['level'] == 0){
								echo "<script>alert('权限不足，请返回')</script>";
								exit;
							}else{
			                	echo $form->field( $model, 'cost_name' )->dropDownList( $comm, [ 'prempt' => '请选择' ] );
			                }
			                ?>
						</div>
						<div class="col-lg-4">
							<?php 
			                if($model['level'] == 0 & $role == 7){
			                	echo $form->field($model,'level')->dropDownList(['父级','子级']);
			                }else{
			                	echo $form->field($model, 'level')->dropDownList([1 => '子级']);
			                }
			                ?>
						</div>
						<div class="col-lg-4">
							<?php 
			                if($role == 7 && empty($model['cost_name'])){
			                	//添加父级费项框  财务经理专用
			                	echo $form->field($model, 'parent')->dropDownList([0 => '父级']);
			                }elseif($role == 14){
			                	//子级费项编辑框  财务专用
			                	echo $form->field($model, 'parent')->dropDownList($cost);
							}else{
			                	echo $form->field($model, 'parent')->dropDownList($cost);
			                }
			             ?>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-3">
							<?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
						</div>
						<div class="col-lg-3">
							<?= $form->field($model, 'inv')->dropDownList(['否','是'],['length' => 1]) ?>
						</div>
						
						<div class="col-lg-3">
							<?= $form->field($model, 'sale')->dropDownList(['否','是'],['length' => 1]) ?>
						</div>
						
						<div class="col-lg-3">
							<?= $form->field($model, 'formula')->dropDownList(['月','面积/月'],['length' => 1]) ?>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<?= $form->field($model, 'property')->textArea(['maxlength' => true, 'placeholder' => '请输入备注信息'])->label(false) ?>
						</div>
					</div>

					<div class="form-group" align="center">
						<?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					</div>
				</td>
				<td width="4%"></td>
			</tr>
	</tbody>
</table>
    <?php ActiveForm::end(); ?>

</div>
