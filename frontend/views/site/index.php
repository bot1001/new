<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '裕家人';

?>
<div>
	<style>
		#home {
			width: 100%;
			background: #F8F8F8;
			margin-top: 10px;
		}
		
		#logo {
			width: 34px;
		}
		
		#td {
			width: 70px;
			text-align: center;
		}
		
		#t {
			width: 90px;
			text-align: center;
		}
		#box{
			display: inline;
		}
		
		#box1,#box2,#box3,#box4,#box5{
			width: 370px;
			height: 300px;
/*			position: relative;*/
			margin-right: 15px;
			margin-bottom: 20px;
			border-radius: 20px;
			background: #BDE0D7;
		}
	</style>

	<table id="home" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td><img id="logo" src="/image/logo01.png"></td>
				
				<td id="td"><?php 
					if (Yii::$app->user->isGuest) {
						$url = Url::to('/login/login');
                        echo Html::a('登录', $url);
                    } else {
						$url = Url::to('/pay/logout');
						echo Html::a(Yii::$app->user->identity->user_name, $url);
					} ?></td>
                                              
				<td id="td"><a href='#'>用户信息</a></td>
				
				<td id="td"><a href='#'>个人资料</a></td>
				
				<td id="td"><a href='#'>房屋缴费</a></td>
				
				<td id="td"><a href='#'>客户服务</a></td>
				
				<td id="t"><a href='#'>裕家人APP</a></td>
				
			</tr>
		</tbody>
	</table>
	
	<div id="box" class="row">
		<div id="box1" class="col-lg-3">
			<?= $this->render('box1') ?>
		</div>
		
		<div id="box2" class="col-lg-3">
			<?= $this->render('box1') ?>
		</div>
		
		<div id="box3" class="col-lg-3">
			<?= $this->render('box1') ?>
		</div>
		
		<div id="box4" class="col-lg-3">
			<?= $this->render('box1') ?>
		</div>
		
		<div id="box5" class="col-lg-3">
			<?= $this->render('box1') ?>
		</div>
	</div>
	
</div>