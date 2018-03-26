<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */

$this->title = $model['id'];
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
    
    <?php ActiveForm::begin([
                'action' => ['pay/pay','status' => $model['status']],
		    	'options'=> ['id' => 'orderconfirm'],
            ]); ?>
      
      <div class="row" id="payment-method-options">
		  <div class="col-lg-2">
              <div class="payment-method-option">
                  <input class="le-radio" type="radio" name="paymethod" value="alipay" checked>
                  <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/zfb.png') ?>" style="height: 31px;width: 115px;"></div>
              </div>
          </div>
          
         <div class="col-lg-2">
             <div class="payment-method-option">
                <input class="le-radio" type="radio" name="paymethod" value="jh">
                <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/wx.png') ?>" style="height: 31px;width: 85px;"></div>
			 </div>
           </div>
            
            <div class="col-lg-2">
             <div class="payment-method-option">
                <input class="le-radio" type="radio" name="paymethod" value="xj">
                <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/xj.png') ?>" style="height: 30px;width: 30px;"></div>
			 </div>
           </div>
            
            <div class="col-lg-2">
             <div class="payment-method-option">
                <input class="le-radio" type="radio" name="paymethod" value="up">
                <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/up.png') ?>" style="height: 30px;width: 45px;"></div>
			 </div>
           </div>
            
            <div class="col-lg-2">
             <div class="payment-method-option">
                <input class="le-radio" type="radio" name="paymethod" value="yh">
                <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/yh.jpg') ?>" style="height: 30px;width: 40px;"></div>
			 </div>
           </div>
            
            <div class="col-lg-2">
             <div class="payment-method-option">
                <input class="le-radio" type="radio" name="paymethod" value="zf">
                <div class="radio-label bold "><img src="<?php echo Url::to('@web/image/zf.png') ?>" style="height: 30px;width: 40px;"></div>
			 </div>
           </div>
            
      </div>
	<hr />
      <div class="place-order-button">
		  <button type="submit" class="btn btn-warning btn-block modal-body"><h3>马上支付</h3></button>
      </div>
	
	<input type="hidden" value="<?php echo $model['order_id']; ?>" name="order_id">
	<input type="hidden" value="<?php echo $model['description']; ?>" name="description">
	<input type="hidden" value="<?php echo $model['order_amount']; ?>" name="order_amount">
	<input type="hidden" value="<?php echo $model['description']; ?>" name="description">
	
	<?php ActiveForm::end(); ?>
        
    <?php 
        /*if( Yii::$app->getSession()->hasFlash('cancel') ) {
			$a = Yii::$app->getSession()->getFlash('cancel');
			echo "<script>alert('$a')</script>";
			
        	echo Alert::widget([
        		'options' => [
        			'class' => 'alert-success', //这里是提示框的class
        		],
        		'body' => $a, //消息体
        	]);
        }elseif(Yii::$app->getSession()->hasFlash('m_order')){
			$m_order = Yii::$app->getSession()->getFlash('m_order');
			echo "<script>alert('未支付订单，暂无更多信息！')</script>";
		}else{
			if($model['status'] != 1 || empty($_SESSION['user']['community'])){
			echo "<script>alert('订单无效或用户权限不足，无法支付')</script>";
		    }
		}*/
		?>

</div>
