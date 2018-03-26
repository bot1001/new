<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CostRelation;
use app\models\CommunityBasic;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-relation-form">
   
    <?php $form = ActiveForm::begin(['id' => 'form-id',
									 'enableAjaxValidation' => true, 
									]); ?>
    
    <?php 
	   $array = Yii::$app->db->createCommand('select cost_id,cost_name from cost_name where parent=0')->queryAll();
	   $cost = ArrayHelper::map($array,'cost_id','cost_name');
	
	   $c = $_SESSION['user']['community'];
	$a = CommunityBasic::find()->select( 'community_id,community_name');
	if($c){
		$array1 = $a->where(['community_id' => $c])->asArray()->All();;
	}else{
	   $array1 = $a->asArray()->All();
	}
	   $comm = ArrayHelper::map($array1,'community_id','community_name');
	?>
<table align="center" style="border-radius: 20px; background-color: #F3F3F3; width: 550px">
	<tbody>
	<tr>
		<td>
			 
		</td>
		<tr>
			<td width="5%"></td>
			<td>
				<div class="row">
					<div class="col-lg-4">
						<?= $form->field($model, 'community')->dropDownList($comm, ['prompt'=>'请选择', 'id'=>'c_id']);?>
					</div>
					<div class="col-lg-3">
						<?= $form->field($model, 'building_id')->widget(DepDrop::classname(), [
                            'type' => DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'b_id'],
	                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions'=>[
                                'depends'=>['c_id'],
                                'placeholder'=>'请选择...',
                                'url'=>Url::to(['/costrelation/b'])
                            ]
                        ]); ?>
					</div>

					<div class="col-lg-3">
						<?= $form->field($model, 'realestate_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'costrelation-id'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['b_id'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/re'])
                ]
            ]); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-4">
						<?= $form->field($model, 'price')->dropDownList($cost,['prompt'=>'请选择','id'=>'costrelation-parent']) ?>
					</div>
					<div class="col-lg-3">
						<?= $form->field($model, 'cost_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'costrelation-price'],
	            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['costrelation-parent'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/costrelation/p'])
                ]
            ]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<?= $form->field($model, 'from', [
                'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                'options'=>['class'=>'drp-container']])
	                     ->widget(DateRangePicker::classname(), [
                'useWithAddon'=>true,
				'pluginOptions'=>[
                'singleDatePicker'=>true,
                'showDropdowns'=>true
                ]
            ]) ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-10">
						<?= $form->field($model, 'property')->textArea(['maxlength' => true]) ?>
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
