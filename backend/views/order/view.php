<?php

use yii\ helpers\ Html;
use yii\ widgets\ DetailView;
use yii\ widgets\ ActiveForm;
use yii\ bootstrap\ Modal;
use yii\ helpers\ Url;
use yii\ bootstrap\ Alert;

/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */

$this->title = $model[ 'id' ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="order-basic-view">
	
	<style>
		img{
			width:100%;
			height: auto;
			border-radius: 20px;
		}

	</style>

	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'id',
			'label' => '序号'],
            
	        ['attribute' => 'address',
			 'label' => '地址',],
	
            ['attribute' => 'order_id',
			'label' => '订单号'],
            //'order_parent',
            ['attribute' =>'create_time',
			 'label' => '创建时间',
			'format' => ['date','php:Y:m:d H:i:s']],
            ['attribute' => 'payment_gateway',
			 'label' => '支付方式',
			 'value'=> function($model){
	            $e = [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行' ];
	            if(empty($model['payment_gateway'])){
	            	return '';
	            }else{
	            	return $e[$model['payment_gateway']];
	            };
            },],
	     
	        ['attribute' => 'payment_number',
			 'label' => '支付单号',
			'value'=> function($model){
            	if(empty($model['payment_number'])){
            		return '';
            	}else{
            		return $model['payment_number'];
            	}
            }],
            
	        ['attribute' => 'description',
			 'label' => '详情',
			 'format' => 'raw',
			 'value' => function($model)
			 {
				 $url = Yii::$app->urlManager->createUrl(['/user-invoice/index','order_id' => $model['order_id']]);
	             $l = explode(',',$model['description'],2);

	             if($model['payment_gateway']){
	             	return Html::a($l['0'],$url).'等';
	             }else{
	             	return '';
	             }
            }],

	        ['attribute' => 'order_amount',
			 'label' => '金额',],
            
	        ['attribute' => 'status',
			 'value' => function($model){
	             $data = [1=>'未支付',2=>'已支付',3=>'已取消', 4=>'送货中', 5=>'已签收', 6 => '其他'];
	             return $data[$model['status']];
             },
	        'label' => '订单状态'],
        ],
        'template' => '<tr><th style="text-align: right">{label}</th><td style="text-align: left">{value}</td></tr>',
    ]) ?>

	<?php 
	if($model['status'] != 1) { exit; };
	
	$pay = ['order_id'=> $model['order_id'],
				  'description'=> $model['address'],
				  'order_amount'=>$model['order_amount'],
				  'status' => $model['status'],
			      'community' => $model['account_id']
		   ]; 
	?>

	<div class="row">
	    <div id="zfb" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'alipay','pay'=> $pay ]) ?>">									  
	    		<img src="/image/zfb.png">
	    	</a>
	    </div>
	    
	    <div id="wx" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'wx','pay'=> $pay ]) ?>">
	    		<img src="/image/wx.png" title="微信支付">
	    	</a>
	    </div>
	    
	    <div id="jh" class="col-lg-3">
	    	<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'jh','pay'=> $pay ]) ?>">
	    		<img src="/image/j.png" title="建行龙支付">
	    	</a>
	    </div>
    
	    <div class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'xj','pay'=> $pay, 'gateway' => '6' ]) ?>">
	    		<img src="/image/xj.png">
	    	</a>
	    </div>
    
	    <div id="up" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'up','pay'=> $pay, 'gateway' => '3' ]) ?>">
	    		<img src="/image/up.png">
	    	</a>
	    </div>
    
	    <div id="yh" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'yh','pay'=> $pay, 'gateway' => '4' ]) ?>">
	    		<img src="/image/yh.png" title="银行代付">
	    	</a>
	    </div>
	    
	    <div id="zf" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'zf','pay'=> $pay, 'gateway' => '5' ]) ?>">
	    		<img src="/image/zf.png" title="政府代付">
	    	</a>
	    </div>

        <div id="zf" class="col-lg-3">
	    	<a href="<?= Url::to(['/pay/pay', 'paymethod' => 'sale','pay'=> $pay, 'gateway' => '8' ]) ?>">
	    		<img src="/image/sale.jpg" title="赠送优惠">
	    	</a>
	    </div>
	</div>
</div>