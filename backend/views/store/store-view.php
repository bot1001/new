<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = '订单详情';
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    #pr{
        width: 75px;
        border-radius:10px;
        border: solid 1px rgba(1, 255, 112, 0.51);
    }
</style>

<script>
    function printme(){
        document.body.innerHTML = document.getElementById('main').innerHTML+ '<br/>';;
        window.print();
    }
</script>
</style>
<div class="store-view">

    <a href="<?= Url::toRoute('order') ?> "> 订单详情 </a>
    <div class="main" id="main">
        <style>
            #main{
                width: 650px;
                background: #fff;
                border-radius: 10px;
                font-size: 16px;
            }
            .status, .address, .time, .logistics, .l, .product, .money{
                margin-left: 10px;
            }
            st{
                font-weight: bolder;
            }
            .status, .time, .product, #logistic{
                display: flex;
            }

            .order_id, .phone, .create_time{
                width: 35%;
            }
            .amount{
                width: 20%;
            }
            .name{
                width: 30%;
            }
            .address{
                padding:10px 0px 10px 0px;
                min-height: 50px;
                background: rgba(128, 128, 128, 0.29);
                max-width: 95%;
            }

            .address, .product, .image, .word, .money, .logistics{
                border-radius: 5px;
            }
            .logistics{
                min-height: 100px;
                background: rgba(128, 128, 128, 0.29);
                max-width: 618px;
            }
            #logistic{
                max-width: 618px;
            }
            .product{
                min-height: 100px;
                background: rgba(128, 128, 128, 0.29);
                width: 95%;
            }
            .image, .word, .count, .money, .order_id, .name, .phone, #pr{
                margin-top: 5px;
            }
            .image{
                margin-left: 10px;
            }
            #img{
                width: 80px;
            }
            .image{
                width: 80px;
                height: 80px;
            }
            .word{
                width: 50%;
                height: 80px;
                margin-left: 10px;
                border:solid 1px #00ca6d;
            }
            .money{
                border: solid 1px #00a2d4;
                height: 80px;
                min-width: 200px;
            }
            .l{
                width: 50%;
            }
            .right{
                text-align: right;
                margin-left: -10%;
            }
            .amount{
                text-align: right;
            }
            .header{
                color: gray;
            }
            .print{
                height: 75px;
                width: 700px;
            }
            #print{
                width: 75px;
                margin: auto;
            }
hr{
    border-top:1px solid #000;
    width: 96%;
    margin: auto;
}
        </style>

        <div class="status">
            <div class="order_id"><?= '订单编号：'.'<st>'.$order_id .'</st>' ?></div>
            <div class="name"><?= '下单人：'.'<st>'.$name.'</st>' ?></div>
            <div class="phone"><?= '联系方式：'.'<st>'.$phone.'</st>' ?></div>
        </div>
<hr />
        <div class="address"><?= '收货地址：'.'<st>'.$address.'</st>'; ?></div>
        <div class="time">
            <div class="create_time">
                <?= '下单时间：'.'<st>'.date('Y-m-d H:i:s', $order_info['create_time']).'</st>' ?>
            </div>
            <div class="create_time">
                <?= '支付时间：'.'<st>'.date('Y-m-d H:i:s', $order_info['payment_time']).'</st>' ?>
            </div>
            <div class="amount"><?= '合计金额：'.'<st>'.$order_info['order_amount'].'</st>' ?></div>
        </div>
<hr />

        <div class="l">产品信息：</div>
        <?php foreach($product as $p): $p = (object)$p ?>
            <div class="product">
                <div class="image"> <img src="<?= $p->image ?>", id="img"> </div>
                <div class="word">
                    <div class=""><?= $p->name ?></div>
                    <div class="header"><?= $p->header ?></div>
                </div>

                <div class="money">
                    <div class="count"><?= '数量：'.$p->count ?></div>
                    <div class="price"><?= '合计：'.$p->price ?></div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <div id="logistic">
            <div class="l">物流信息：</div>
            <div class="l right">运费： <st>25</st></div>
        </div>
        <div class="logistics"> </div>
    </div>

    <div class="print">
        <div id="print">
            <a href="javascript:printme()" rel="external nofollow" target="_self">
                <img src='/image/print.png' id="pr">
            </a>
        </div>
    </div>
</div>