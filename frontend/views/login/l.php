<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '用户登录';
?>
<style>
	
	.site-login{
		height: 600px;
		background-color: #F5F5F5;
		border-radius: 20px;
	}
	
	h3, #row{
		text-align: center;
	}
	#row{
		height: 330px;
		width: 330px;
		background: #EEF9F7;
		border-radius: 5px;
		border:1px solid #7DE8E6;
	}
	#phone, #password, #forget, table{
		margin: auto;
		text-align: center;
		width: 80%;
		position: relative;
		top: 20px;
	}
	#remember{
		display: none;
	}
	h3{
		position: relative;
		top: 10px;
	}
	img{
		width: 40px;
		height: auto;
	}
	
	#forget{
		text-align: right;
		position: relative;
		top: 10px;
	}
	
	#login{
		position: relative;
		top: 20px;
	}
	
	table{
		width: 80%;
		margin: auto;
	}
	table tr{
		height: 50px;
	}
</style>

<div class="site-login" align="right">

        <?php $form = ActiveForm::begin([
        	'id' => 'login-form',
        	'fieldConfig' => [
        	'template' => '{input}{error}'],
        ]); ?>
        
		<div id="row">
		    <h3><?= Html::encode('用户登录') ?></h3>
		    
			<div id="phone"><?= $form->field($model, 'mobile_phone')->textInput(['autofocus' => true, 'placeholder' => '请输入登录手机号码']) ?></div>
			
			<div id="password"><?= $form->field($model, 'password')->passwordInput(['placeholder' => '请输入登录密码']) ?></div>
			
			<div id="remember"><?= $form->field($model, 'rememberMe')->checkbox() ?></div>
			
            <div id="forget">
				<a href="<?= Url::to(['/site/pr']) ?>">忘记密码</a>
            </div>

            <div id="login" style="width: 80%; margin: auto">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>
            
			<div>
				<table>
					<tr>
						<td align="left"><a href="https://open.weixin.qq.com/connect/qrconnect?appid=wx61eec3717a800533&redirect_uri=http://www.gxydwy.com&response_type=code&scope=snsapi_login&state=YES#wechat_redirect"><img src="/image/wx.png"></a></td>
						<td align="right"><a href="#">立即注册</a></td>
					</tr>
				</table>
			</div>
			
        </div>
        <?php ActiveForm::end(); ?>
    <?php 
//	if(isset($_SESSION)){
//		echo '<pre>'; print_r($_SESSION);
//	}	
	 ?>
</div>
