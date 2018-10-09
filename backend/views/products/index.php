<?php

use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '确认';
$this->params['breadcrumbs'][] = $this->title;

//引入模态窗文件
//echo $this->render('..\..\..\common\modal\modal.php');
?>
<script>
    function pay(method, way) {
        document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" style='border-radius: 20px'/><br />请稍后……"; //图片切换和加载

        $.ajax({
            type: "GET",
            dataType: "html",
            url:"/order/create",
            data:{paymethod: method, order: <?= $order ?>, realestate: <?= $realestate ?>, gateway: way},
            success: function (s) {
                if(s == '1'){
                    if(method == 'wx'){
                        <?php $img = $order.'_wx' ?>
                    }else if(method == 'jh'){
                        <?php $img = $order.'_jh' ?>
                    }
                    document.getElementById('QR').innerHTML = "<img src=\"\\images\\<?= $img ?>.png\" id=\"qr\" /><br /><div id='div2'>支付二维码！</div>";
                }else if (s == '2') {
                    document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" /><br />支付金额有误，请确认！";
                    clearInterval( intervalId ); //清除定时器
                }else{
                    document.getElementById('QR').innerHTML = "<img src=\"\\image\\logo_108.png\" id=\"qr\" /><br />二维码过期，请重新获取！";
                    clearInterval( intervalId ); //清除定时器
                }
            },
        })

        function find() {
            var xhr = new XMLHttpRequest();
            if(method == 'wx'){//xml请求参数
                xhr.open( 'GET', "<?= Url::to(['/pay/wei', 'order_id' => $order]); ?>", true );
            }else if(method == 'jh'){
                xhr.open( 'GET', "<?= Url::to(['/pay/jhang', 'order_id' => $order]); ?>", true );
            }
            xhr.onload = function () {
                if ( this.responseText == '1' ) {
                    document.getElementById( 'div2' ).innerHTML = '<a href= "<?= Url::to(['/order/print', 'order_id' => $order]); ?>">支付成功！</a>';
                    clearInterval( intervalId ); //清除定时器
                }

                if ( this.responseText == '' ) {
                    document.getElementById( 'div2' ).innerHTML = '<l>等待支付中,请稍后……</l>';
                }
            }
            xhr.send();//发送请求
        }

        var intervalId = setInterval( function () {//定时器 2秒
            find();
        }, 2000 );
    }
</script>
<style>
    .products-index{
        max-width: 600px;
        border: solid #00a0e9 1px;
        border-radius: 10px;
    }
    th, td{
        text-align: center;
    }
    .sure{
        max-width: 600px;
        text-align: center;
        font-size: 40px;
        margin-top: 30px;
    }
    .img{
        width: 70px;
        border-radius: 20px;
    }
    .yes{
        margin-left: 0px;
        display: flex;
    }
    .QR{
        position: relative;
        left: 20px;
        background: #f2dede;
        width: 400px;
        height: 400px;
        border-radius: 5px;
        text-align: center;
        font-size: 25px;
        font-weight: bolder;
    }
    #qr{
        margin-top: 80px;
        border-radius: 20px;
        width: 250px;
    }
    .remind{
        position: relative;
        bottom: -190px;
        /*color: #00ca6d;*/
    }
</style>

<?php
if ( Yii::$app->getSession()->hasFlash( 'cancel' ) ) {
    $a = Yii::$app->getSession()->getFlash( 'cancel' );
    echo "<script>alert('$a')</script>";
}
?>
<div class="yes">
    <div>
        <div class="products-index">
            <?php
            $gridview = [
                ['class' => 'kartik\grid\SerialColumn', 'header' => '序号'],
                ['attribute' => 'product_name',
                    'pageSummary' => '合计：'],

                ['attribute' => 'product_price',
                    'class' => 'kartik\grid\EditableColumn',
                    'editableOptions' => [
                        'formOptions' => [ 'action' => [ '/products/products' ] ],
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    ],
                    'pageSummary' => true],
            ];

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'showPageSummary' => true,
                'columns' => $gridview,
            ]); ?>
        </div>

        <div>
            <div class="sure">
                <a href="<?= Url::to(['/order/create', 'paymethod' => 'alipay','order'=> $order, 'realestate' => $realestate ]) ?>" title="支付宝">
                    <img src="\image\zfb.png" class="img">
                </a>

                <a href="#" title="微信" onclick="pay('wx', '')"><img src="\image\wx.png" class="img"></a>

                <a href="#" title="龙支付" onclick="pay('jh', '')"><img src="\image\j.png" class="img"></a>

                <a href="#" title="刷卡"  onclick="pay('up', '3')"><img src="\image\up.png" class="img"></a>

                <a href="#" title="银行代付" onclick="pay('yh', '4')"><img src="\image\yh.png" class="img"></a>

                <a href="#" title="现金" onclick="pay('xj', '6')"><img src="\image\xj.png" class="img"></a>
            </div>
        </div>
    </div>

    <div class="QR" id="QR">
<!--        <img src="\images\QR_0_181007987213.png" id="qr" />-->
        <div class="remind">请确定充值金额！<br /></div>
    </div>
</div>



