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

	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'id',
			'label' => '序号'],
            
	        ['attribute' => 'ad',
			 'label' => '地址',],
	
            ['attribute' => 'order_id',
			'label' => '订单号'],
            //'order_parent',
            ['attribute' =>'create_time',
			 'label' => '创建时间',
			'format' => ['date','php:Y:m:d H:i:s']],
            //'order_type',
            //'payment_time:datetime',
            ['attribute' => 'payment_gateway',
			 'label' => '支付方式',
			 'value'=> function($model){
	            $e = [1 => '支付宝',2 => '微信', 3=> '刷卡' ,4 => '银行', '5'=>'政府', 6 => '现金', 7 => '政府'];
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
			 'value' => function($model){
				 $url = Yii::$app->urlManager->createUrl(['/user-invoice/index1','order_id' => $model['order_id']]);
	             $l = explode(',',$model['description'],2);
	             return Html::a($l['0'],$url).'等';
            }],
	
	       /*['attribute' => 'order.product_name',
			'label' => '名称',
			 'format' => 'raw',
			 'value' => function($model){
				 $url = Yii::$app->urlManager->createUrl(['/user-invoice/index1','order_id' => $model['order_id']]);
	             return Html::a('点击查看',$url);
            }],*/
	
	        ['attribute' => 'order_amount',
			 'label' => '金额',],
            
	        ['attribute' => 'status',
			 'value' => function($model){
	             $data = [1=>'未支付',2=>'已支付',3=>'已取消', 4=>'送货中', 5=>'已签收', 6 => '其他'];
	             return $data[$model['status']];
             },
	        'label' => '订单状态'],
            //'status',
        ],
    ]) ?>

	<?php $pay = ['order_id'=> $model['order_id'],
				  'description'=> $model['description'],
				  'order_amount'=>$model['order_amount'],
				  'status' => $model['status']]; 
	?>

	<div class="row">
			<div id="zfb" class="col-lg-2">
			<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'alipay','pay'=> $pay ]) ?>">									  
			<img src="/image/zfb.png" style="width:100%;height: auto">
		  </a>
	</div>
	
	<div id="jh" class="col-lg-2">
			<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'wx','pay'=> $pay ]) ?>">
			<img src="/image/wx.png" style="width:100%;height: auto">
		  </a>
	</div>

	<div class="col-lg-2">
			<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'xj','pay'=> $pay ]) ?>">
			<img src="/image/xj.png" style="width:100%;height: auto">
		  </a>
	</div>

	<div id="up" class="col-lg-2">
		<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'up','pay'=> $pay ]) ?>">
			<img src="/image/up.png" style="width:100%;height: auto">
		  </a>
	
	</div>

	<div id="yh" class="col-lg-2">
		<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'yh','pay'=> $pay ]) ?>">
			<img src="/image/yh.png" style="width:100%;height: auto">
		  </a>
	
	</div>
	<div id="zf" class="col-lg-2">
		<a href="<?php echo Url::to(['/pay/pay', 'paymethod' => 'zf','pay'=> $pay ]) ?>">
			<img src="/image/zf.png" style="width:100%;height: auto">
		  </a>
	
	</div>

	</div>
</div>