<?php 

$this->title = '微信支付';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

use yii\helpers\Url;
?>

<style>
	#wx{
		height: 410px;
		width: 410px;
		position: fixed;
		top: 50%;
		left: 50%;
		margin: -295px 0 0 -205px;
	}
	
	#remind{
		text-align: center;
		width: 290px;
		font-size: 20px;
	}
	
	k {
		font-weight: 1000;
		color: red;
		font-size: 25px
	}
	p{
		font-size: 20px;
	}
	
	img{
		border-radius: 10px;
	}
</style>

<script>
	function loadText() {
		var xhr = new XMLHttpRequest();
		//xml请求参数
		xhr.open( 'GET', "<?php echo Url::to(['/pay/wei', 'order_id' => $order_id]); ?>", true );
		xhr.onload = function () {
				if ( this.responseText == '1' ) {
					document.getElementById( 'div2' ).innerHTML = '<a href= "<?php echo Url::to(['/order/print', 
																								 'order_id' => $order_id, 'amount' => $order_amount]); ?>">支付成功！</a>';
					clearInterval( intervalId ); //清除定时器
				}

				if ( this.responseText == '' ) {
					document.getElementById( 'div2' ).innerHTML = "<p></p><l>等待支付中,请稍后……</l>";
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

<div align="center">

   <div id="wx">
     <img src="<?= '/'.$img ?>"/>
      <div id="div2">
          <p><?php echo '订单编号：'.'<k>'.$order_id.'</k>'.'<br />'.'合计：'.'<k>'.$order_amount.'</k>'; ?></p>
      </div>
      <div id="remind" style="color: darkred">
	     <p></p>
	     请用微信客户端扫描支付二维码
	  </div>
	</div>    
</div>
