<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '个人中心';

?>
<div>
	<style>
		#box{
			display: inline;
		}
		
		#box1,#box2,#box3,#box4,#box5{
			width: 370px;
			height: 300px;
			margin-right: 15px;
			margin-bottom: 20px;
			border-radius: 20px;
			background: #BDE0D7;
		}
	</style>
	
	<div id="box" class="row">
	<?php if(isset($_SESSION['user'])) { ?>
		<div id="box1" class="col-lg-3">
			 
			<?=  $this->render('box1'); ?>
		</div>
		
<!--		<div id="box2" class="col-lg-3">
			<?php // echo $this->render('box1') ?>
		</div>
		
		<div id="box3" class="col-lg-3">
			<?php // echo $this->render('box1') ?>
		</div>
		
		<div id="box4" class="col-lg-3">
			<?php // echo $this->render('box1') ?>
		</div>
		
		<div id="box5" class="col-lg-3">
			<?php // echo $this->render('box1') ?>
		</div>-->
	<?php } ?>
	</div>
</div>