<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = '建行支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#button").click(); }, 5000);
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
		   font-size: 20px;
		   text-align: center;
		   position:absolute;top:74%;left: 40%;
	   }
	   k{
		   font-weight: 1000;
		   color: red;
		   font-size: 25px
	   }
	   
	   button{
		   display:none;
	   }
</style>
  
  <button id="button">查询</button>
 
 <script>
	 document.getElementById('button').addEventListener("click", loadText);
	 
	 function loadText()
	{ 
		 var xhr = new XMLHttpRequest();
		 //xml请求参数
		 xhr.open('GET', "<?php echo Url::to(['/pay/jhang', 'order_id' => $order_id]); ?>", true);
		 xhr.onload = function(){
			 if(this.responseText == '1'){
				 document.getElementById('div2').innerHTML = '<a href= "<?php echo Url::to(['/order/print', 'order_id' => $order_id]); ?>">支付成功！</a>';
			 }
			 
			 if(this.responseText == '0' ){
				 document.getElementById('div2').innerHTML = '<l>等待支付中,请稍后……</l>';
			 }
		 }
		 //发送请求
		 xhr.send();
	 }
</script>
   
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