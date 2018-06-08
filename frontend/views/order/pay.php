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
	#pay{
		width:200px;
		height: auto;
		border-radius: 20px;
		}	
</style>
	
<div class="order-index">

<?php
	$pay = ['order_id'=> $order['order_id'],
		'description'=> $order['description'],
		'order_amount'=>$order['order_amount']];
	?>

	    <div id="zfb" class="col-lg-4">
	    	<a href="<?php echo Url::to(['/pay/pay', 'method' => 'alipay','pay'=> $pay ]) ?>">									  
	    		<img id="pay" src="/image/zfb.png">
	    	</a>
	    </div>
	    
	    <div id="wx" class="col-lg-4">
	    	<a href="<?php echo Url::to(['/pay/pay', 'method' => 'wx','pay'=> $pay ]) ?>">									  
	    		<img id="pay" src="/image/wx.png">
	    	</a>
	    </div>
	    
	    <div id="jh" class="col-lg-4">
	    	<a href="<?php echo Url::to(['/pay/pay', 'method' => 'jh','pay'=> $pay ]) ?>">									  
	    		<img id="pay" src="/image/jh.png">
	    	</a>
	    </div>
</div>