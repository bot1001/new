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
	
/*	鼠标滑过主题样式*/
	.dropdown {
          position: relative;
          display: inline-block;
    }
	
/*	鼠标滑过显示内容样式*/
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 120px;
	    font-size: 20px;
	    border-radius: 10px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
        padding: 12px 16px;
	    z-index: 1;
    }
	
/*	显示内容样式*/
    .dropdown:hover .dropdown-content {
        display: block;
    }
	
</style>

<script>
	function wx(){
		alert("暂不支持，敬请期待！")
	}
</script>

<div class="order-index">
    
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
				<a href="#<?php // echo Url::to(['/pay/pay', 'method' => 'wx','pay'=> $pay ]) ?>"  title="微信支付" onmouseover="show()">
					<div class="dropdown">
						<span>
							<img id="pay" src="/image/wx.png" onClick="wx()" />
						</span>
						<div class="dropdown-content">
							微信支付
						</div>
					</div>				  
	            </a>			
			</td>

			<td>
				<a href="<?php echo Url::to(['/pay/pay', 'method' => 'jh','pay'=> $pay ]) ?>" title="龙支付">
            	    <div class="dropdown">
						<span>
							<img id="pay" src="/image/j.png" />
						</span>
						<div class="dropdown-content">
							建行龙支付
						</div>
					</div>									  
	            </a>			
			</td>
		</tr>
	</table>
</div>