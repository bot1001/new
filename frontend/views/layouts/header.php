<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

     <style>
		#home {
			margin: auto;
			width: 1190px;
			background: #F0F0F0;
			margin-top: 10px;
			border-radius: 5px;
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
	
    	#header{
    		background: #46F0FF;
    		height: 80px;
    	}
    	#advertising{
    		background: #46F0FF;
    	}
		 l{
			 color: red;
		 }
    </style>

<div id="header">
	<div id="advertising" align="center">
		 <img src="/images/5.jpg">
	</div>
</div>


<table id="home" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
				    <a href="<?= Url::to(Yii::$app->homeUrl) ?>">
				         <img id="logo" src="/image/logo01.png">
				    </a>
				</td>
				
				<td id="td"><?php 
					if (Yii::$app->user->isGuest) {
						$url = Url::to('/login/login');
                        echo Html::a('登录', $url);
                    } else {
						$url = Url::to('/pay/logout');
						echo Html::a(Yii::$app->user->identity->user_name, $url);
					} ?></td>
                                              
				<td id="td"><a href='<?= Url::to('/personal/index') ?>'>个人中心</a></td>
				
				<td id="td"><a href='#'>房屋资料</a></td>
				
				<td id="td"><a href='<?= Url::to('/invoice/index')?>'>房屋缴费</a></td>
				
				<td id="td"><a href='#'>客户服务</a></td>
				
				<td id="t"><a href='#'>裕家人APP</a></td>
				
			</tr>
		</tbody>
	</table>