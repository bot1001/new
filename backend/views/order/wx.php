<?php 

$this->title = '微信支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div align="center">
	<?php
	echo $img;
	?>
	<div style="color: darkred">请用手机微信扫描支付二维码</div>
</div>
