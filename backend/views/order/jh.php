<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = '建行支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 5);
});
JS;
$this->registerJs($script);

?>
  
   <!-- <meta http-equiv='refresh' content='1'/>;  // php 自带的页面刷新功能-->
   
   <style>
	   #div1{
		   width: 500px;
		   height: 500px;
		   text-align: center;
		   position:absolute;top:20%;left: 40%;
		   border-radius:20px;
		   background: url(/image/jh.png);
		   background-color: aqua;
	   }
	   #qr{
		   border-radius: 20px;
		   position: relative;top: 26%;
	   }
	   #div2{
		   height: 50px;
		   width: 500px;
		   #background: #9A2729;
		   font-size: 20px;
		   text-align: center;
		   position:absolute;top:74%;left: 40%;
	   }
	   k{
		   font-weight: 1000;
		   color: red;
		   font-size: 25px
	   }
	   
</style>
   
<div>
    <div id="div1">
    	<div id="qr" >
    		<img src='<?php echo Url::to("@web/$f"); ?>' style="height: 280px;width: 280px; border-radius: 20px">
    	</div>
    	
    </div>
	<div id="div2">
		<?php echo '订单编号：'.'<k>'.$order_id.'</k>'.'&nbsp&nbsp&nbsp'.'合计金额：'.'<k>'.$order_amount.'</k>';
		?>
	</div>
</div>
<?php Pjax::begin(); ?>
   <?php
       //echo '<g>'.Html::a("北京时间：", ['/pay/jhang'], ['id' => 'refreshButton']);
   ?>
<?php Pjax::end() ?>