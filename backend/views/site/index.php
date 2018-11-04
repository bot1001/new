<?php

use backend\assets\AppAsset;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */

$this->title = '裕家人';
AppAsset::addCss($this,Yii::$app->request->baseUrl."/css/home.css");
?>

    <!-- 投诉信息 -->
    <?php
		if (Helper::checkRoute('/ticket/index')){ ?>
    	<div id="box1" class="col-lg-3 box">
    	    <?= $this->render('box1'); ?>
	    </div>
	<?php } ?>
   	
   	<!-- 注册信息 -->
   	<?php
	   if(Helper::checkRoute('/user/index')){ ?>
    	<div id="box2" class="col-lg-3 box">
    		<?= $this->render('box2'); ?>
    	</div>
    <?php } ?>
    
    <!-- 缴费信息 -->
   	<?php
	   if(Helper::checkRoute('/order/index')){ ?>
    	<div id="box3" class="col-lg-3 box">
    		<?= $this->render('box3'); ?>		    
    	</div>
    <?php } ?>
      
       <div id="box4" class="col-lg-3 box">
       	   <?= $this->render('box4'); ?>   		   
       </div>
      
	   <div id="box5" class="col-lg-3 box">
	       <?= $this->render('box5') ?>   	
	   </div>

       <div id="box6" class="col-lg-3 box">
	       <?= $this->render('box6') ?>
	   </div>