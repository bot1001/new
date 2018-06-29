<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

$this->title = '房屋添加';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '个人中心', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>


<style>
	#wx {
		display: none;
	}
	
	.login-form{
		width: 500px;
		font-size: 18px;
		border-radius: 5px;
		margin: auto;
	}
</style>

<div class="login-form" style="background: #76DFCD">
  
		<?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-lg-4">
				<?= $form->field($data, 'province_id')->dropDownList( $province, ['prompt' => '请选择', 'id' => 'province']) ?>
			</div>

			<div class="col-lg-4">
				<?= $form->field($data, 'city_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'city'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['province'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/area/city'])
                    ]
                ]); ?>
			</div>

			<div id="test" class="col-lg-4">
				<?= $form->field($data, 'area_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'area'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['city'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/area/city'])
                    ]
                ]); ?>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-3">
				<?= $form->field($realestate, 'community_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'community'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['area'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/area/community'])
                    ]
                ]) ?>
			</div>

			<div class="col-lg-3">
				<?= $form->field($realestate, 'building_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'building'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['community'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/realestate/b'])
                    ]
                ]); ?>
			</div>

			<div class="col-lg-3">
				<?= $form->field($realestate, 'room_number')->widget(DepDrop::classname(), [
                   'type' => DepDrop::TYPE_SELECT2,
                   'options'=>['id'=>'number'],
	               'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                   'pluginOptions'=>[
                       'depends'=>['building'],
                       'placeholder'=>'请选择...',
                       'url'=>Url::to(['/realestate/b2'])
                   ]
               ]); ?>
			</div>

			<div class="col-lg-3">
				<?= $form->field($realestate, 'room_name')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'name'],
	                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['number'],
                        'placeholder'=>'请选择...',
                        'url'=>Url::to(['/realestate/re']),
	                    'params'=>['building'], //另一个上级目录ID
                    ]
                ]); ?>
			</div>

		</div>

		<div class="row">
			<div class="col-lg-4"><?= $form->field($realestate, 'owners_name')->textInput(['maxlength' => true, 'placeholder' => '验证业主姓名', 'id' => 'owners'])->label(false) ?>
			</div>

			<div class="col-lg-4">
				<?= $form->field($realestate, 'phone')->textInput(['maxlength' => true, 'placeholder' => '验证手机号码', 'id' => 'phone'])->label(false) ?>
			</div>
		</div>

		<div id="submit" align="center">
			<input type="submit" value="保存" disabled class="btn info" ></input>
		</div>

		<?php ActiveForm::end(); ?>
 
    
    <script>
		var oBtn = document.getElementById('phone');
		var oBt = document.getElementById('name');
		var oB = document.getElementById('owners');
		
		if('onkeydown' in oBtn){ //监听器
                oBtn.addEventListener("change", phone, false); 
                oBt.addEventListener("change", phone, false); 
                oB.addEventListener("change", phone, false); 
            }else{ 
                oBtn.onpropertychange = getWord; 
                oB.onpropertychange = getWord; 
            }
		
		function phone() {
			o = oBtn.value+','+oBt.value+','+oB.value;
            $.ajax({
                type: "POST",//方法类型
                dataType: "json",//预期服务器返回的数据类型
                url: "/site/phone" ,//验证地址
                data: o,
                success: function (result) {
                    if (result == 1) {
						document.getElementById( 'submit' ).innerHTML = '<input type="submit" value="确定" class="btn info"></input>';
						alert("信息验证成功！");
                    };
                },
                error : function() {
					document.getElementById( 'submit' ).innerHTML = '<input type="submit" disabled value="确定" class="btn info"></input>';
                    alert("手机号码或姓名验证失败！");
                }
            });
        }
    </script>  
</div>