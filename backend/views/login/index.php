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
	table{
		width:400px;
		height: auto;
		height:auto; 
		padding: 250px;
        background-color: rgba(255,250,240,0.7);
		#opacity: 0.8;
		border-radius:20px;
		font-size: 15px; /*字体*/
        text-align: center;
		position: fixed;
		top: 50%;
		left: 50%;
		margin: -175px 0 0 -175px;
	}
	
	img{
		height: 70px;
		width: 70px; 
		border-radius: 10px;
	}
	
	th{
		text-align: center;
		font-family: 仿宋;
	}

</style>

<div class="site-login">

    <?php
    $form = ActiveForm::begin( [
    	'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_INLINE
    ] );
    ?>
	<table>
		<thead>
		    <tr>
			     <th colspan = "2"><br />
		    <tr>
			     <th width="30%"><img src="/image/logo01.png"></th>
			     <th style="text-align: left; font-size: 36px;">裕家人</th>
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
				
				<div style="color: black">
					<?= $form->field($model, 'rememberMe')->checkbox() ?>
				</div>
			    <br>
				<?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
           </td>
			<tr>
				<td colspan="2"><br /></td>
			</tr>
		</tr>
	</table>
    <?php ActiveForm::end(); ?>   
</div>