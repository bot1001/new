<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<script type="text/javascript">
        function login() {
            $.ajax({
                type: "POST",//方法类型
                dataType: "json",//预期服务器返回的数据类型
                url: "/login/login" ,//url
                data: $('#login').serialize(),
                success: function (result) {
                    if (result == 1) {
                        alert("登录成功！");
                    };
                },
            });
        }
	//定时器 0.01秒
	intervalId = setInterval( function () {
		login();
	}, 50 );
	
    </script>
    
<style>
	.user-account-form{
		display: none;
	}
</style>


<div class="user-account-form">

    <?php $form = ActiveForm::begin(['id' => 'login', 'action' => '/pay/login']); ?>

    <?= $form->field($model, 'mobile_phone')->textInput(['maxlength' => true, 'value' => $phone]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'value' => $password]) ?>

    <div class="form-group">
        <?= Html::submitButton('登录', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>