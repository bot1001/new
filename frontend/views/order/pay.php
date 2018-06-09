<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缴费记录';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<style>
	#pay {
		width: 180px;
		height: auto;
		border-radius: 20px;
	}
	
	.payment {
		width: 600px;
		margin: auto;
	}
	
	#box, #z{
		display: none;
		position:absolute
	}
</style>

<div class="order-index">

    <div id="box">微信</div>
    <div id="z">建设银行</div>
    
	<?php
	$pay = [ 'order_id' => $order[ 'order_id' ],
		'description' => $order[ 'description' ],
		'order_amount' => $order[ 'order_amount' ]
	];
	?>
	<table class="payment">
		<tr>
			<td>
				<a href="<?php echo Url::to(['/pay/pay', 'method' => 'alipay','pay'=> $pay ]) ?>">									  
	            	<img id="pay" src="/image/zfb.png">
	            </a>			
			</td>

			<td>
				<a href="<?php echo Url::to(['/pay/pay', 'method' => 'wx','pay'=> $pay ]) ?>"  title="微信支付" onmouseover="show()">									  
	            	<img id="pay" src="/image/wx.png" />
	            </a>			
			</td>

			<td>
				<a href="<?php echo Url::to(['/pay/pay', 'method' => 'jh','pay'=> $pay ]) ?>" title="龙支付">									  
	            	<img id="pay" src="/image/j.png" />
	            </a>			
			</td>
		</tr>
	</table>
</div>