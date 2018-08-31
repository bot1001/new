<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .advertising{
        background: #ffffff;
        border-radius: 5px;
        margin-left: 10px;
    }

	#ad_form{
        margin-left: 10px;
		border-radius: 5px;
		min-height: 500px;
		background: #FFFFF0
	}

	#title{
		font-size: 25px;
		text-align: center;
		height: 30px;
		font-size: 30px;
	}

    img{
        width: 100%;
        border-radius: 10px;
    }

    #img{
        margin-top: 30px;
        width: 100%;
        border-radius: 15px;
    }

</style>

<div class="advertising-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-lg-7 advertising">
            <br />
            <?= $form->field($model, 'ad_title')->textInput(['maxlength' => true, 'id' => 'btn', 'placeHolder' => '请输入标题'])->label(false)   ?>

            <?= $form->field($model, 'ad_excerpt')->widget('kucha\ueditor\UEditor',['clientOptions' =>['initialFrameHeight' => '300']])->label(false)  ?>

			<div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'ad_poster')->widget('common\widgets\upload\FileUpload')->label(false) ?>
                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'ad_sort')->input('number', ['placeholder' => '排序'])->label(false) ?>
                    <?= $form->field($model, 'ad_end_time', [
                        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                        'options'=>['class'=>'drp-container']])
                        ->widget(DateRangePicker::classname(), [
                            'useWithAddon'=>true,
                            'pluginOptions'=>[
                                'singleDatePicker'=>true,
                                'showDropdowns'=>true,
                                'useWithAddon'=>true,
                            ]
                        ])->textInput(['placeholder' => '截止时间'])->label(false) ?>
                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'ad_type')->dropDownList(['1' => '文章', '2' => '链接'], ['prompt' => '请选择类型'])->label(false) ?>

                    <?= $form->field($model, 'ad_location')->dropDownList(['1' => '顶部', '2' => '底部'], ['prompt' => '请选择位置'])->label(false) ?>
                </div>

                <div class="col-lg-3">
                    <?= $form->field($model, 'ad_publish_community')->dropDownList($_SESSION['community_id'], ['multiple'=>'multiple'], ['prompt' => '请选择'])->label(false) ?>
                </div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?= $form->field($model, 'property')->textarea(['rows' => 3, 'placeholder' => '备注'])->label(false) ?>
				</div>
			</div>

			<div class="form-group" align="center">
				<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span>', ['title' => '保存', 'class' => 'btn btn-success']) ?>
			</div>
		</div>

		<div id="ad_form" class="col-lg-3">
			<div id="title">标题<?= $model->ad_title ?></div>
            <div id="view"> <img src="<?= $model->ad_poster ?>" id="img" /> </div>
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