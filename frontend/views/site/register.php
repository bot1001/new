<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use yii\ widgets\ ActiveForm;
use kartik\ depdrop\ DepDrop;
use kartik\ select2\ Select2;

/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */
/* @var $form yii\widgets\ActiveForm */

$this->title = '用户注册';
?>



<style>
	#wx {
		display: none;
	}
	
	.login-form{
		width: 50%;
		font-size: 18px;
		border-radius: 5px;
		margin: auto;
	}
</style>

<h1 align="center"><?= Html::encode($this->title) ?></h1>

<div class="login-form" style="background: #76DFCD">
    <table style="border-radius: 50px">
    	<tbody>
    		<tr>
    			<td>
					<?php $form = ActiveForm::begin([
                    	'action' => ['/login/new','w_info' => $w_info]
                    ]); ?>

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

						<div class="col-lg-4">
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
                                    'url'=>Url::to(['/realestate/re'])
                                ]
                            ]); ?>
						</div>

					</div>

					<div id="test" class="row">
						<div class="col-lg-4">
							<?= $form->field($realestate, 'phone')->textInput(['maxlength' => true, 'placeholder' => '验证手机号码', 'id' => 'phone'])->label(false) ?>
						</div>

						<div class="col-lg-4">
							<?= $form->field($realestate, 'owners_name')->textInput(['maxlength' => true, 'placeholder' => '验证业主姓名', 'id' => 'owners'])->label(false) ?>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-4">
							<?= $form->field($account, 'user_name')->textInput(['maxlength' => true, 'placeholder' => '昵称'])->label(false) ?>
						</div>
						
						<div class="col-lg-3">
							<?= $form->field($data, 'gender')->dropDownList(['1' => '男', '2' => '女'])->label(false) ?>
						</div>

						<div class="col-lg-4">
							<?= $form->field($account, 'mobile_phone')->textInput(['maxlength' => true, 'placeholder' => '登录手机号码'])->label(false) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-5">
							<?= $form->field($account, 'password')->input('password',['maxlength' => true, 'placeholder' => '请输入登录密码'])->label(false) ?>
						</div>

						<div class="col-lg-5">
							<?= $form->field($account, 'new_pd')->input('password',['maxlength' => true, 'placeholder' => '请再次输入密码'])->label(false) ?>
						</div>

						<!--	隐藏内容起	-->
						<div id="wx">
							<?= $form->field($account, 'weixin_openid')->textInput(['maxlength' => true, 'value' => $w_info['openid']]) ?>
						</div>

						<div id="wx">
							<?= $form->field($account, 'account_id')->textInput(['maxlength' => true, 'value' => $k]) ?>
						</div>

						<div id="wx">
							<?= $form->field($account, 'wx_unionid')->textInput(['maxlength' => true, 'value' => $w_info['unionid']]) ?>
						</div>

						<div id="wx">
							<?= $form->field($data, 'face_path')->textInput(['maxlength' => true, 'value' => $w_info['headimgurl']]) ?>
						</div>
						<!--	隐藏内容止	-->
					</div>

					<div id="submit" align="center">
						<input type="submit" value="确定" disabled class="btn info"></nput>
					</div>

					<?php ActiveForm::end(); ?>
    			</td>
    		</tr>
    	</tbody>
    </table>
    
    <script>
		var oBtn = document.getElementById('phone');
		
		if('onkeydown' in oBtn){ 
                oBtn.addEventListener("change",getWord,false); 
            }
		
		function change() {
            $.ajax({
                type: "POST",//方法类型
                dataType: "json",//预期服务器返回的数据类型document.getElementById( 'div2' ).innerHTML = '<l>等待支付中,请稍后……</l>';
                url: "/site/phone" ,//url
                data: oBtn.value,
                success: function (result) {
                    if (result == 1) {
//                        alert("手机号码验证成功！");
						document.getElementById( 'submit' ).innerHTML = '<input type="submit" value="确定" class="btn info"></nput>';
                    };
                },
                error : function() {
                    alert("手机号码验证失败！");
                }
            });
        }
		
        function getWord(){
			change();
        }

    </script>
    
</div>