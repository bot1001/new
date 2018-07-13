<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '个人中心';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<style>
		#box{
			display: inline;
		}
		
		#box1,#box2,#box3,#box4,#box5{
			width: 360px;
		    height: 350px;
			margin-right: 15px;
			margin-bottom: 20px;
			border-radius: 20px;
		}
		
		#box1{
			background: #A4ECD0;
		}
		
		#box2{
			background: #46F0FF;
		}
	</style>
	
	<div id="box" class="row">
	<?php if(isset($_SESSION['user'])) { ?>
		<div id="box1" class="col-lg-4">
			<?=  $this->render('box1'); ?>
		</div>
		
		<div id="box2" class="col-lg-4">
			<?= $this->render('box2',['data' => $data, 'order' => $order]) ?>
		</div>
		
		<div id="box3" class="col-lg-3">
			<?= $this->render('box3') ?>
		</div>
		
		<!--<div id="box4" class="col-lg-4">
			<?php // echo $this->render('box1') ?>
		</div>
		
		<div id="box5" class="col-lg-4">
			<?php // echo $this->render('box1') ?>
		</div>-->
	<?php } ?>
	</div>
	
	<?php 
	$message = Yii::$app->getSession()->getFlash('success');
	if($message == 1){
		echo "<script>alert('切换成功！')</script>";
	} ?>
</div>