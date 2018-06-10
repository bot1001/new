<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use yii\ widgets\ Breadcrumbs;

?>

<style>
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
	
	.header {
		margin: auto;
		height: 40px;
		width: 1190px;
		background: #F0F0F0;
		border-radius: 5px;
	}
		
	li a:hover {
		background-color: #C7C7C7;
	}
	
/*	活动菜单加亮*/
	.active {
		border-radius: 5px;
		background-color: #4CAF50;
	}
	
/*	面包屑属性设置*/
	#breader{
		height: 40px;
		background: #F7F7F7;
	}
	
	nav ul {
		font-size: 17px;
		float: right;
		list-style-type: none;
		border-radius: 5px;
		background-color: skyblue;
		padding: 0 20px;
		position: relative;
		box-shadow: 1px 1px 3px #666;
		z-index: 1;
	}
	
	nav ul li {
		float: left;
	}
		
	nav ul li a {
		display: block;
		padding: 7px 15px;
	}
	
	nav ul li:hover> ul {
		display: block;
	}
	
	nav ul li:hover {
		color: #FFF;
		background: linear-gradient(to bottom, #4f5964, #5f6975);
	}
	
	nav ul li:hover a {
		color: #FFF;
		border-radius: 5px;
	}
	
	nav ul ul {
		float: right;
		display: none;
		background: #5f6975;
		border-radius: 5px;
		position: absolute;
		top: 100%;
		padding: 0;
	}
	
	center{
		color: black;
		font-weight: 700;
		font-size: 20px;
	}
</style>

<script>
	function logout() {
		if(confirm('您确定要退出吗？')){
            $.ajax({
                type: "POST",//方法类型
                dataType: "json",//预期服务器返回的数据类型
                url: "/pay/logout" ,//url
                data: '',
                success: function (result) {
                    if (result == 1) {
                        alert("退出成功！");
						location.reload();
                    };
                },
                error : function() {
                    alert("服务器异常，请联系管理员！");
                }
            });
        }
	}
</script>


<div id="header">
	<div id="advertising" align="center">
		<img src="/images/5.jpg">
	</div>
</div>

<p></p>

<div class="header">
	<div id="breader" class="col-lg-3">
		<?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
    </div>
    
    <nav>
	    <ul>
           <li><?php 
	           if (Yii::$app->user->isGuest) {
	           	$url = Url::to('/login/login');
                      echo Html::a('登录', $url);
                  } else {
//	           	$url = Url::to('/pay/logout');
	           	echo Html::a(Yii::$app->user->identity->user_name, '#', ['onclick' => "logout()"]);
	           } ?>
       	   </li>
	       			    
           <li><a href='<?= Url::to('/personal/index ') ?>'>个人中心</a></li>
           
           <li><a href='#'>房屋资料</a></li>
           
           <li><a href='<?= Url::to('/invoice/index ')?>'>房屋缴费</a>
               <ul>
               	   <li><a href="<?= Url::to('/order/index ')?>">缴费记录</a></li>
               </ul>
           </li>
           
           <li><a href='#'>客户服务</a>
               <ul>
               	   <li><a href="<?= Url::to('/order/index ')?>">投诉</a></li>
               	   <li><a href="<?= Url::to('/order/index ')?>">建议</a></li>
               </ul>
           </li>
           
           <li><a href="<?= Url::to('/site/load ')?>">裕家人APP</a>
               <ul>
                   <li style="background: #D6E1D9; border-radius: 5px;"><a href="<?= Url::to('/site/load ')?>"><img src="/image/QR.png" /></a>
                       <center>扫码下载裕家人APP</center>
                   </li>
               </ul>
           </li>               
        </ul>
    </nav>
</div>