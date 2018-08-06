<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\daterange\DateRangePicker;

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
            <?= $form->field( $model, 'company' )->dropDownList(\common\models\Company::Company(), ['prompt' => '请选择', 'id' => 'company'])->label(false);?>
        </div>

        <div class="col-lg-1">
            <?= $form->field( $model, 'user_name' )->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'branch'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['company'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/company/branch'])
                ]
            ])->label(false); ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field( $model, 'community' )->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options'=>['id'=>'community'],
                'select2Options'=>['pluginOptions'=> ['multiple' => true, 'allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['branch'],
                    'placeholder'=>'请选择...',
                    'url'=>Url::to(['/company/c'])
                ]
            ])->label(false);?>
        </div>


		<div class="col-lg-2">
			<?= $form->field( $model, 'fromdate', [
                    'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                    'options'=>['class'=>'drp-container']])
	                         ->widget(DateRangePicker::classname(), [
                    'useWithAddon'=>true,
		        	'pluginOptions'=>[
                        'locale'=>[
                            'separator'=>' to ',
                        ],
//                    'singleDatePicker'=>true,
                    'showDropdowns'=>true
                    ],
                ])->textInput(['placeholder' => '请选择统计时间'])->label(false);
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