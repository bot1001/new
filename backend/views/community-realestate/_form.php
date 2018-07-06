<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use kartik\form\ ActiveForm;
use app\ models\ CommunityBasic;
use yii\ helpers\ ArrayHelper;
use kartik\ depdrop\ DepDrop;
use kartik\ select2\ Select2;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="community-realestate-form">

	<?php 
	$c = $_SESSION['community'];
	if($c){
		$Array = CommunityBasic::find()->select( 'community_id,community_name')->where(['community_id' => $c])->All();;
	}else{
	   $Array = CommunityBasic::find()->select( 'community_id,community_name')->All();
	}   
	$comm = ArrayHelper::map($Array,'community_id','community_name');
	?>

	<?php $form = ActiveForm::begin(); ?>
<table border="0" align="center" style="border-radius: 20px; background-color: #F3F3F3; width: 550">
	<tbody>
	<tr>
		<td>
			 
		</td>
	</tr>
		<tr>
		<td width="5%"></td>
			<td>
				<div class="row">
					<div class="col-lg-4">
						<?= $form->field($model, 'community_id')->dropDownList($comm,['prompt'=>'请选择','id'=>'community']) ?>
					</div>
					<div class="col-lg-2">
						<?= $form->field($model, 'building_id')->widget(DepDrop::classname(), [
                            'type' => DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'building'],
	                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                'depends'=>['community'],
                                'placeholder'=>'请选择...',
                                'url'=>Url::to(['/costrelation/b'])
                            ]
                        ]); ?>
					</div>
					<div class="col-lg-2">
						<?= $form->field($model, 'room_number')->widget(DepDrop::classname(), [
                            'type' => DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'number'],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                'depends'=>['building'],
                                'placeholder'=>'请选择...',
                                'url'=>Url::to(['/costrelation/number'])
                            ]
                        ]); ?>
					</div>
					<div class="col-lg-3">
						<?= $form->field($model, 'room_name')->textInput(['placeholder' => '请输入……']) ?>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-3">
						<?= $form->field($model, 'owners_name')->textInput(['maxlength' => true, 'placeholder' => '请输入……']) ?>
					</div>
					<div class="col-lg-3">
						<?= $form->field($model, 'owners_cellphone')->textInput(['maxlength' => true, 'placeholder' => '请输入……']) ?>
					</div>
					
					<div class="col-lg-3">
						<?= $form->field($model, 'acreage')->textInput(['maxlength' => true, 'placeholder' => '房屋面积']) ?>
					</div>
					
					<div class="col-lg-2">
						<?= $form->field($model, 'orientation')->textInput(['maxlength' => true, 'placeholder' => '朝向']) ?>
					</div>
				</div>

				<div class="row">
		      		<div class="col-lg-3">
		      			<?= $form->field($model, 'finish', [
                            'addon'=>['prepend'=>['content'=>'']],
                            //'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                            'options'=>['class'=>'drp-container']])
	                                 ->widget(DateRangePicker::classname(), [
                                'useWithAddon'=>true,
			                	'pluginOptions'=>[
                                    'singleDatePicker'=>true,
                                    'showDropdowns'=>true,
					        	    'useWithAddon'=>true,
                                ]
                            ]) ?> 
		      		</div>
		      		
		      		<div class="col-lg-3">
		      			<?= $form->field($model, 'delivery', [
                             'addon'=>['prepend'=>['content'=>'']],
                             'options'=>['class'=>'drp-container']])
	                                  ->widget(DateRangePicker::classname(), [
                                 'useWithAddon'=>true,
			                 	'pluginOptions'=>[
                                     'singleDatePicker'=>true,
                                     'showDropdowns'=>true,
					         	    'useWithAddon'=>true,
                                 ]
                             ]) ?> 
		      		</div>
		      		
		      		<div class="col-lg-3">
		      			<?= $form->field($model, 'decoration', [
                            'addon'=>['prepend'=>['content'=>'']],
                            'options'=>['class'=>'drp-container']])
	                                 ->widget(DateRangePicker::classname(), [
                                'useWithAddon'=>true,
			                	'pluginOptions'=>[
                                    'singleDatePicker'=>true,
                                    'showDropdowns'=>true,
					        	    'useWithAddon'=>true,
                                ]
                            ]) ?> 
		      		</div>		      			
				</div>
      		
	      		<div class="row">
		      		<div class="col-lg-5">
		      			<?= $form->field($house, 'IDcard')->textInput(['length' => '18', 'placeholder' => '请输入身份证……']); ?>
		      		</div>
				</div>
	      		
	      		<div class="row">
		      		<div class="col-lg-9">
		      			<?= $form->field($house, 'address')->textArea(['placeholder' => '请输入地址……']) ?>
		      		</div>					
		      	</div>
            	
            	<div class="row">
					<div class="col-lg-11">
						<?= $form->field($model, 'property')->textInput(['maxlength' => true, 'placeholder' => '请输入相对应的信息……']) ?>
					</div>
				</div>

				<div class="form-group" align="center">
					<?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
	<?php ActiveForm::end(); ?>

</div>