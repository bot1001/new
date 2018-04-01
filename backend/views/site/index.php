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
</style>
  
<div style="background-color: #E5F5F3;border-radius: 20px; height: 100vh">
  <br>
   <h1>广西裕达物业服务有限公司</h1>
   <h4>祝您工作愉快！</h4>
   
   <div class="jumbotron">

   </div>

    <?php
    	if($name == 'admin'){
    		echo "<h5>欢迎您，<h>超级管理员！</h></h5>";
    	}else{
    		echo "<h5>欢迎您，<h>$name ！</h></h5>";
    	}
        echo "<br />";
        echo "<h5>您的登录地址是：<h>$a</h></h5>";
    	
        Pjax::begin();
    	
    	echo '<g>'.Html::a("北京时间：", ['index'], ['id' => 'refreshButton']);
    	echo date('Y-m-d H:i:s').'</g>';
    	Pjax::end(); 
    	
	//echo (round(1/3,4)*100).'%'; php字符串相除并保留两个小数点
    	?>
	<a href="<?php echo Url::to(['/user-invoice/search']); ?>"> <h5>缴费统计</h5></a>
	<a href="<?php echo Url::to(['/user-invoice/sum']); ?>"> <h5>新缴费统计</h5></a>
	
	
	<?php
	   $i =1;
	$date = '2018-01';
	for($i=1; $i<=13; $i++)
	{
		$test = date('Y-m', strtotime("+$i month", strtotime($date)));
		echo $test.'<br />';
	}
	
	?>
	
</div>