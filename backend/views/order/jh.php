<?php
use yii\ helpers\ Url;
use yii\ helpers\ Html;
use yii\ widgets\ Pjax;

$this->title = '建行支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

?>

<style>
	#div1 {
		width: 500px;
		height: 500px;
		text-align: center;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;
		border-radius: 20px;
		background: url(/image/jh.png);
		background-color: aqua;
	}
	
	#qr {
		border-radius: 20px;
		position: relative;
		top: 26%;
	}
	
	#div2 {
		height: 50px;
		width: 500px;
		font-size: 20px;
		text-align: center;
		position: fixed;
		top: 550px;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;
	}
	
	k {
		font-weight: 1000;
		color: red;
		font-size: 25px
	}
	
	button {
		display: none;
	}
</style>

<script>
	function loadText() {
		var xhr = new XMLHttpRequest();
		//xml请求参数
		xhr.open( 'GET', "<?php echo Url::to(['/pay/jhang', 'order_id' => $order_id]); ?>", true );
		xhr.onload = function () {
				if ( this.responseText == '1' ) {
					document.getElementById( 'div2' ).innerHTML = '<a href= "<?php echo Url::to(['/order/print', 
																								 'order_id' => $order_id, 'amount' => $order_amount]); ?>">支付成功！</a>';
					clearInterval( intervalId ); //清除定时器
				}

				if ( this.responseText == '0' ) {
					document.getElementById( 'div2' ).innerHTML = '<l>等待支付中,请稍后……</l>';
				}
			}
			//发送请求
		xhr.send();
	}
	
	//定时器 2秒
	intervalId = setInterval( function () {
		loadText();
	}, 2000 );
</script>

<div>
	<div id="div1">
		<div id="qr">
			<img src='<?php echo Url::to("@web/$f"); ?>' style="height: 280px;width: 280px; border-radius: 20px">
		</div>

	</div>
    <p></p>
	<div id="div2">

		<?php echo '订单编号：'.'<k>'.$order_id.'</k>'.'<br />'.'合计：'.'<k>'.$order_amount.'</k>';?>
	</div>
</div>