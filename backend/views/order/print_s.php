<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/5
 * Time: 14:43
 */
$this->title = '打印';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<script>
    function printme() {
        document.body.innerHTML = document.getElementById( 'market' ).innerHTML + '<br/>';
        window.print();
    }
</script>

<style>
    .print_m{
        min-height: 500px;
        background: #ffffff;
        max-width: 1000px;
        margin: auto;
        border-radius:10px;
    }
    .print{
        background: #ffffe5;
        max-width: 700px;
        margin: auto;
        border-radius: 5px;
        position: relative;
        top: 30px;
    }
    .detail{
        width: 95%;
        margin: auto;
    }
    .p{
        text-align: center;
        margin: auto;
        width: 100px;
        background: #fff9e5;
        border-radius: 15px;
        border: solid 1px rgba(0, 128, 0, 0.1);
        position: relative;
        bottom: -50px;
    }
    img{
        width: 75px;
        border-radius:20px;
    }
</style>
<div class="print_m">
    <div class="print">
        <span id="market">
            <div class="detail" style="font-family: 仿宋">
                <style>
                    h3{
                        text-align: center;
                        font-family: 仿宋;
                        font-weight: 820;
                        text-decoration: underline;
                        margin-bottom: 20px;
                    }
                    .row{
                        width: 665px;
                        display: flex;
                        margin: auto;
                    }

                    #d{
                        width: 665px;
                        margin: auto;
                    }

                    table tr th, td{
                        text-align: center;
                        border: solid 1px;
                    }
                    #address{
                        width: 35%;
                    }
                    #name{
                        width: 20%;
                    }
                    #order_id{
                        width: 25%;
                    }
                    #way{
                        width: 20%;
                    }
                    .manager, .time{
                        width: 30%;
                    }
                    .company{
                        width: 40%;
                        text-align: right;
                    }
                    .right{
                        text-align: right;
                    }
                    .left{
                        text-align: left;
                    }
                </style>
                <h3><?= $comm['community'];  ?></h3>
                <div class="row">
                    <div id="address"> 房号：<?= $address ?></div>
                    <div id="name">业主姓名：<?= $comm['n'] ?></div>
                    <div id="order_id">订单号：<?= $order_id ?></div>
                    <div id="way" style="text-align: right;"><?= '收款方式：'.$e[$order['payment_gateway']]; ?></div>
                </div>

                <table id="d">
                    <tr>
                        <th style="width: 50px">序号</th>
                        <th>名称</th>
                        <th style="width: 50px">数量</th>
                        <th style="width: 100px">金额</th>
                        <th>备注</th>
                    </tr>
                    <?php
                        $k = 0; //序号默认
                        foreach ($invoice as $in){
                            $k ++;
                            ?>
                            <tr>
                                <td><?= $k ?></td>
                                <td class="left"><?= $in['product_name'] ?></td>
                                <td><?= $in['product_quantity'] ?></td>
                                <td class="right"><?= $in['product_price'] ?></td>
                                <td class="left"></td>
                            </tr>
                    <?php } ?>

                    <tr width="665px" >
                        <td colspan="4" class="right">合计：  &nbsp;&nbsp;&nbsp;<?= $amount; ?></td>
                        <td></td>
                    </tr>
                </table>
                <div class="row">
                    <div class="manager">收款人：<?= $user_name; echo '('.Yii::$app->request->userIP.')'; ?></div>
                    <div class="time"><?= '时间：'.date('Y-m-d H:i:s', $order['payment_time']) ?></div>
                    <div class="company">广西裕达集团物业服务有限公司(盖章)</div>
                </div>
            </div>
        </span>
    </div>
    <div class="p">
        <a href="javascript:printme()" rel="external nofollow" target="_self">
            <img src='/image/print.png'>
        </a>
    </div>
</div>
