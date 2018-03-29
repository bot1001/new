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
	#div1{
		margin: auto;
		width:350px; 
		height:auto; 
		background: url(image/th.png);
		border-radius:20px;
	}
	
	img{
		height: 70px;
		width: 70px; 
		border-radius: 10px;
	}
	
	th{
		text-align: center;
		font-size: 36px;
		font-family: 仿宋;
	}

   #table_wrap > table {
            font-size: 15px; /*字体*/
            text-align: center;
        }	
</style>

<div id="div1" class="site-login">

    <?php
    $form = ActiveForm::begin( [
    	'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_INLINE
    ] );
    ?>
<div id="table_wrap">
	<table class="table">
		<thead>
		  <tr>
			  <th width="30%"><img src="/image/logo01.png"></th>
			  <th style="text-align: left">裕家人</th>
		 </tr>
		 
		</thead>

		<tr>
			<td colspan="2">
                <br>
				<div>
					<?= $form->field($model, 'name') ?>
				</div>
				<br>
				<div>
					<?= $form->field($model, 'password')->passwordInput() ?>
				</div>
				
				<div style="color: aliceblue">
					<?= $form->field($model, 'rememberMe')->checkbox() ?>
				</div>
			    <br>
				<?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
           </td>
		</tr>
	</table>
</div>
    <?php ActiveForm::end(); ?>
    
</div>