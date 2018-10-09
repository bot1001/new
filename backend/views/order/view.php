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
        #pay{
            width: 25%;
        }
        .pay{
            display: flex;
        }
        #success{
            margin: auto;
        }

        #QR{
            text-align: center;
            margin-bottom: 10px;
        }

        #qr{
            border: solid 1px #00ca6d;
            border-radius: 20px;
            width: 250px;
        }

        #div2, #success{
            margin: auto;
            text-align: center;
            font-size: 20px;
            font-weight: bolder;
            margin-bottom: 10px;
            color: red;
        }
	</style>

    <div id="QR">
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
    </div>

	<?php
	if($model['status'] != 1) { exit; };

	$order = $model['order_id'];
	?>
    <script>
        function pay(method, gateway) {
            document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" style='border-radius: 20px'/>"; //图片切换和加载
            $.ajax({
                Type:'GET',
                dataType:'json',
                url:'/pay/pay',
                data:{order_id:<?= $model['order_id'] ?>, description:"<?= $model['address'] ?>", order_amount:<?= $model['order_amount'] ?>, status:<?= $model['status'] ?>, community:<?= $model['account_id'] ?>, paymthod:method, gateway:gateway},
                success: function (result) {
                    if(result == '1'){
                        if(method == 'wx'){
                            document.getElementById('QR').innerHTML = "<img src=\"\\images\\<?= $order.'_wx' ?>.png\" id=\"qr\" />";
                        }else if(method == 'jh'){
                            document.getElementById('QR').innerHTML = "<img src=\"\\images\\<?= $order.'_jh' ?>.png\" id=\"qr\" />";
                        }else if(method == 'alipay'){
                            document.getElementById('QR').innerHTML = "<img src=\"\\images\\<?= $order.'_ali' ?>.png\" id=\"qr\" />";
                        }
                    }else if(result == '0'){
                        document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" /><br />二维码过期，请重新获取！";
                        clearInterval( intervalId ); //清除定时器
                    }else {
                        document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" /><br />系统错误，请联系管理员！";
                        clearInterval( intervalId ); //清除定时器
                    }
                }
            })

            function find() {
                var xhr = new XMLHttpRequest();
                if(method == 'wx'){//xml请求参数
                    xhr.open( 'GET', "<?= Url::to(['/pay/wei', 'order_id' => $order]); ?>", true );
                }else if(method == 'jh'){
                    xhr.open( 'GET', "<?= Url::to(['/pay/jhang', 'order_id' => $order]); ?>", true );
                }else{
                    xhr.open('GET', "<?= Url::to(['/pay/alipay', 'order_id' => $order, 'trade' => '', 'or' => '']); ?>", true );
                }
                xhr.onload = function () {
                    if ( this.responseText == '' ) {
                        //如果返回信息为空则不做任何操作
                    }else if ( this.responseText == '1' ) {
                        document.getElementById( 'success' ).innerHTML = '<a href= "<?= Url::to(['/order/print', 'order_id' => $order]); ?>">支付成功！</a>';
                        clearInterval( intervalId ); //清除定时器
                    }else{
                        var name = JSON.parse(this.responseText);
                        document.getElementById( 'div2' ).innerHTML = name.buyer;
                    }
                }
                xhr.send();//发送请求
            }

            var intervalId = setInterval( function () {//定时器 2秒
                find();
            }, 2000 );
        }
    </script>

    <div id="success">
        <div id="div2"></div>
        <div class="pay">
            <div id="pay">
                <a href="#"  onclick="pay('alipay','1')"><img src="/image/zfb.png"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('wx','2')">	<img src="/image/wx.png" title="微信支付">	</a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('jh','')">	<img src="/image/j.png" title="建行龙支付"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('xj','6')"><img src="/image/xj.png"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('up','3')"><img src="/image/up.png"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('yh','4')"><img src="/image/yh.png" title="银行代付"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('zf','5')"><img src="/image/zf.png" title="政府代付"></a>
            </div>

            <div id="pay">
                <a href="#" onclick="pay('sal','8')"><img src="/image/sale.jpg" title="赠送优惠"></a>
            </div>
        </div>
    </div>
</div>