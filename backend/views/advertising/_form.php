<?php

use yii\ helpers\ Html;
use yii\ widgets\ ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
	#ad_form{
		border-radius: 5px;
		min-height: 500px; 
		background: #FFFFF0
	}
	p{
		font-size: 25px;
	}
</style>

<div class="advertising-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div class="col-lg-12">
					<?= $form->field($model, 'ad_title')->textInput(['maxlength' => true, 'id' => 'btn'])   ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?= $form->field($model, 'ad_excerpt')->widget('kucha\ueditor\UEditor',['clientOptions' =>['initialFrameHeight' => '300', 'id' => 'test']])  ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<?= $form->field($model, 'ad_poster')->fileInput() ?>
					
				</div>
				<div class="col-lg-3">
					<?= $form->field($model, 'ad_publish_community')->dropDownList($_SESSION['community_id'], ['multiple'=>'multiple'], ['prompt' => '请选择']) ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?= $form->field($model, 'ad_target_value')->textarea(['rows' => 3]) ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-2">
					<?= $form->field($model, 'ad_type')->dropDownList(['1' => '文章', '2' => '链接'], ['prompt' => '请选择']) ?>
				</div>
				<div class="col-lg-2">
					<?= $form->field($model, 'ad_location')->dropDownList(['1' => '顶部', '2' => '底部'], ['prompt' => '请选择']) ?>
				</div>
				<div class="col-lg-1">
					<?= $form->field($model, 'ad_sort')->input('number') ?>
				</div>
				<div class="col-lg-2">
					<?= $form->field($model, 'ad_status')->dropDownList(['1' => '正常', '2' => '删除'], ['prompt' => '请选择']) ?>
				</div>

				<div class="col-lg-3">
					<?= $form->field($model, 'ad_end_time')->textInput() ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?= $form->field($model, 'property')->textarea(['rows' => 3]) ?>
				</div>
			</div>
			
			<div class="form-group" align="center">
				<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span>', ['class' => 'btn btn-success']) ?>
			</div>
		</div>

		<div id="ad_form" class="col-lg-4">
			<div>
				<p id="title">标题</p>
			</div>
			<div>
				你好，世界！<div id="ad_detail"></div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
    <script>
		
        var oBtn = document.getElementById('btn');
        var oTi = document.getElementById('title');
        if('oninput' in oBtn){ 
                oBtn.addEventListener("input",getWord,false); 
            }else{ 
                oBtn.onpropertychange = getWord; 
            }
        function getWord(){
            oTi.innerHTML = oBtn.value;
        }
    </script>
</div>