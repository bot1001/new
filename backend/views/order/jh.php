<?php 
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '建行支付';
//$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>

 <?php Pjax::begin(); ?>
   <!-- <meta http-equiv='refresh' content='1'/>;  // php 自带的页面刷新功能-->
   
   <style>
	   #div1{
		   width: 300px;
		   text-align: center;
		   position:absolute;top:30%;left: 45%;
	   }
</style>
   
<div id="div1">
	<div id="qr" >
		<img src='<?php echo Url::to("@web/$f"); ?>' style="height: 280px;width: 280px; border-radius: 20px">
	</div>
	
	<div style="color: darkred;font-size: 20px">
       <br />
	   建议使用<g style="color:red;font-weight:bold"> 建行 </g>手机客户端扫描
	</div>
</div>
<?php Pjax::end() ?>