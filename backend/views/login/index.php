<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\models\user;

$this->title = '用户登录';

?>

<style type="text/css">
	.login{
		width:400px;
		height: 300px;
        background-color: rgba(255,250,240,0.7);
		#opacity: 0.8;
		border-radius:15px;
		font-size: 15px; /*字体*/
        text-align: center;
		position: fixed;
		top: 50%;
		left: 50%;
		margin: -175px 0 0 -175px;
	}
    .one{
        display: flex;
        margin-left: 20%;
        margin-top: 20px;
    }
	img{
		height: 60px;
		border-radius: 10px;
	}
    .key{
        margin-top: 30px;
    }
    .yu{
        text-align: left;
        font-size: 30px;
        margin-top: 10px;
    }
    .password{
        margin-top: 10px;
    }
    #login{
        display: inline-flex;
        margin-left: 40px;
        margin-top: 20px;
    }
    .remember{
        margin-top: 5px;
        margin-left: 25px;
    }
    #content{
        display: inline-flex;
    }
    .forget{
        text-decoration: underline;
    }
    .register{
        margin-left: 20px;
        text-decoration: underline;
    }
    .glyphicon{
        color: lightseagreen;
    }

    .dropdown {
        position: relative;
        top: 43px;
        left: 90px;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        min-width: 200px;
        font-size: 16px;
        border-radius: 5px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.8);
        padding: 12px 16px;
        z-index: 1;
        background: rgba(255, 219, 11, 0.3);
    }
    /*	显示内容样式*/
    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>

<script>
    function forget() {
        alert('抱歉，此功能尚未开通！');
    }
</script>

<div class="site-login">

    <?php
    $form = ActiveForm::begin( [
    	'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_INLINE
    ] );
    ?>
    <div class="login">
        <div class="one">
            <div><img src="/image/logo01.png"></div>
            <div class="yu">裕家人</div>
        </div>

        <div class="key">
            <div><?= $form->field($model, 'name') ?></div>
            <div class="password"><?= $form->field($model, 'password')->passwordInput() ?></div>
        </div>

        <div id="login">
            <div class="lg"><?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?></div>
            <div class="remember"><?= $form->field($model, 'rememberMe')->checkbox() ?></div>
        </div>

        <div class="dropdown">
            <span class="glyphicon glyphicon-eye-open text-red"></span>
            <div class="dropdown-content">
                <div id="content">
                    <div class="forget"><?= Html::a('忘记密码', '#', ['onclick' => 'forget()']) ?></div>
                    <div class="register"><?= Html::a('商户注册', '/store/register', [ 'title' => '裕家人商家注册入口']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>   
</div>