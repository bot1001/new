<?php 
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '建行支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

 <?php Pjax::begin(); ?>
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
		   position: relative;top: 40%;
	   }
</style>
   
<div id="div1">
	<div id="qr" >
		<img src='/image/jianh.png' style="height: 140px;width: 140px; border-radius: 20px">
	</div>
</div>
<?php Pjax::end() ?>