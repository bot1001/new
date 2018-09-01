<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .row{
        display:flex;
    }
    .advertising{
        background: #ffffff;
        border-radius: 5px;
        margin-left: 10px;
    }

	#ad_form{
		border-radius: 5px;
		min-height: 300px;
        width: 350px;
		background: #FFFFF0;
        position: fixed;
        top: 30%;
        left: 80%;
        margin: -175px 0 0 -175px;
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

    .view{
        margin-top: 30px;
        border: 1px solid yellow;
        border-radius: 10px;
        background: #ffffff;
        width: 270px;
        min-height: 50%;
    }

    .phone-view{
        margin: auto;
        overflow-y: auto;
        height: 500px;
        width: 300px;
    }

</style>

<div class="advertising-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-lg-7 advertising">
            <br />
            <?= $form->field($model, 'ad_title')->textInput(['maxlength' => true, 'id' => 'title02', 'placeHolder' => '请输入标题'])->label(false)   ?>

            <?= $form->field($model, 'ad_excerpt')->widget('kucha\ueditor\UEditor',['clientOptions' =>['initialFrameHeight' => '300']])->label(false)  ?>

			<div style="display: flex">
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

                <div style="width: 10%; ">
                    <?= $form->field($model, 'ad_type')->dropDownList(['1' => '文章', '2' => '链接'], ['prompt' => '请选择类型'])->label(false) ?>

                    <?= $form->field($model, 'ad_location')->dropDownList(['1' => '顶部', '2' => '底部'], ['prompt' => '请选择位置'])->label(false) ?>
                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'ad_target_value')->dropDownList([1=>'APP',2=>'PC', 3=> '微信'], ['multiple'=>'multiple'], ['prompt' => '请选择'])->label(false) ?>
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
    </div>

    <div id="ad_form">
        <div id="title"><?= $model->ad_title ?></div><br />
        <div class="phone-view">
            <div class="view"><?= $model->ad_excerpt ?></div>
        </div>
    </div>

	<?php ActiveForm::end(); ?>
    <script>
		var oBtn = document.getElementById('title02');
        var oTi = document.getElementById('title');
        var v = document.getElementById('btn');

        function view() {
            alert(v.value);
        }

		if('oninput' in oBtn){ 
             oBtn.addEventListener("input",getWord, false);
         }else{
             oBtn.onpropertychange = getWord;
         }

        function getWord(){
			oTi.innerHTML = oBtn.value;
        }
    </script>
</div>