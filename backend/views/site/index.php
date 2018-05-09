<?php

use app\models\UserInvoice;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 300000);
});
JS;
$this->registerJs($script);

/* @var $this yii\web\View */

$this->title = '裕达物业';
?>

  <style>
	  h1{
		  text-align:center;
		  font-weight:bold;
	  }
	  h4{
		  color: #0DE842;
		  font-size: 24px;
		  text-align:center;
		  font-style: italic;
	  }
	  h5{
		  font-size: 24px;
		  margin-left: 10%;
	  }
	  g{
		  font-size: 24px;
		  margin-left: 10%;
	  }
	  h{
		  font-size: 20px;
		  color: #C80F12;
	  }
	  
	  #box1{
		  height: 400px;
		  width: 30%;
		  background: #0EEB5E;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #box2{
		  height: 400px;
		  width: 400px;
		  background: #0EEB5E;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #box3{
		  height: 400px;
		  width: 400px;
		  background: #0EEB5E;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
</style>
  
<div style="background-color: #E5F5F3;border-radius: 20px; height: 100vh">
    
    	<div id="box1" class="col-lg-3">
    		<h4 style="color: #000000">未处理投诉量：<a href="#"><l><?php 
				if(isset($_SESSION['_ticket']['account'])){
					echo $_SESSION['_ticket']['account'];
				}else{
					echo '<h4 style="color: #000000">'.'你们小区的业主真好，居然没有投诉！'.'</h4>';
				}
				?></l>例</a></h4>
    		
    		<?php
			
			if(isset($_SESSION['_ticket']['ticket']))
			{
				$i = 0;
				$k = 0;
				$ticket = $_SESSION['_ticket']['ticket'];
				foreach($ticket as $t)
				{
					$_t[$k]['community'] = $t[0]['0'];
					$_t[$k]['building'] = $t['1']['0'];
					$_t[$k]['room'] = $t['2']['0'];
					$_t[$k]['time'] = $t['3']['0'];
					$k ++;
				}	
			} ?>
   		<?php if(isset($_t)){
	        foreach($_t as $ticket);
			$i++;
			?>
   		<div style="font-size: 18px;">
   			<?php
	if(isset($_t)){
			echo $i;
			echo $ticket['community'].' '.$ticket['building'].' '.$ticket['room'].' '.date('Y-m-d H:i:d', $ticket['time']).'<br />'; 
	}
       } ?>
   		</div>
    		
    	</div>
    	
    	<div id="box2" class="col-lg-3">
    		
    	</div>
    	
    	<div id="box2" class="col-lg-3">
    		
    	</div>
       
       <div class="jumbotron">
    
       </div>

	<a href="<?php //echo Url::to(['/user-invoice/search']); ?>"> <h5><!-- 缴费统计 --></h5></a>
	<a href="<?php //echo Url::to(['/user-invoice/sum']); ?>"> <h5><!-- 新缴费统计 --></h5></a>
</div>