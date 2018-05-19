<?php

use app\models\UserInvoice;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\assets\AppAsset;

/* @var $this yii\web\View */

$this->title = '裕达物业';
AppAsset::addCss($this,Yii::$app->request->baseUrl."/css/home.css");
?>
  
   <div style="background-color: #E5F5F3;border-radius: 20px;">
    
    	<div id="box1" class="col-lg-3">
    		<?= $this->render('box1'); ?>
	    </div>
	    
    	<div id="box2" class="col-lg-3">
    		<?= $this->render('box2'); ?>
    	</div>
    	
    	<div id="box3" class="col-lg-3">
    		<?= $this->render('box3'); ?>		    
    	</div>
    	
       <div id="box4" class="col-lg-3">
       	   <?= $this->render('box4'); ?>   		   
       </div>
      
	   <div id="box5" class="col-lg-3">
	       <?= $this->render('box5') ?>   	
	   </div>
	   
	<a href="<?php //echo Url::to(['/user-invoice/search']); ?>"> <h5><!-- 缴费统计 --></h5></a>
	<a href="<?php //echo Url::to(['/user-invoice/sum']); ?>"> <h5><!-- 新缴费统计 --></h5></a>
 </div>