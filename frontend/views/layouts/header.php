<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use yii\ widgets\ Breadcrumbs;

?>

<style>
	#home {
		text-align: right;
		font-size: 16px;
		margin-left: auto;
		width: 550px;
		margin-top: 6px;
	}
		
	#header {
		background: #46F0FF;
		height: 80px;
	}
	
	#advertising {
		background: #46F0FF;
	}
	
	l {
		color: red;
	}
	
	.breader .list{
		width: 595px;
		font-size: 16px;
	}
	
	.header {
		margin: auto;
		height: 40px;
		width: 1190px;
		background: #F0F0F0;
		border-radius: 5px;
	}
	
</style>

<div id="header">
	<div id="advertising" align="center">
		<img src="/images/5.jpg">
	</div>
</div>

<p></p>

<div class="header row">
	<div class="breader col-lg-6">
		<?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
	</div>

	<div class="list col-lg-6">
		<table id="home" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<?php 
				    if (Yii::$app->user->isGuest) {
				    	$url = Url::to('/login/login');
                        echo Html::a('登录', $url);
                    } else {
				    	$url = Url::to('/pay/logout');
				    	echo Html::a(Yii::$app->user->identity->user_name, $url);
				    } ?>
				</td>

				<td><a href='<?= Url::to('/personal/index ') ?>'>个人中心</a></td>

				<td><a href='#'>房屋资料</a></td>

				<td><a href='<?= Url::to('/invoice/index ')?>'>房屋缴费</a></td>

				<td><a href='#'>客户服务</a></td>

				<td><a href='#'>裕家人APP</a></td>

			</tr>
		</table>
	</div>
</div>