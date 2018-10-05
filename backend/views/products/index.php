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
        border-radius: 10px;
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
        border-radius: 5px;
        width: 250px;
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

                <a href="<?= Url::to(['/order/create', 'paymethod' => 'wx','order'=> $order, 'realestate' => $realestate ]) ?>" title="微信">
                    <img src="\image\wx.png" class="img">
                </a>

                <a href="<?= Url::to(['/order/create', 'paymethod' => 'jh','order'=> $order, 'realestate' => $realestate ]) ?>" title="龙支付">
                    <img src="\image\j.png" class="img">
                </a>

                <a href="<?= Url::to(['/order/create', 'paymethod' => 'up','order'=> $order, 'realestate' => $realestate, 'gateway' => '3']) ?>" title="刷卡">
                    <img src="\image\up.png" class="img">
                </a>

                <a href="<?= Url::to(['/order/create', 'paymethod' => 'yh','order'=> $order, 'realestate' => $realestate, 'gateway' => '4']) ?>" title="银行代付">
                    <img src="\image\yh.png" class="img">
                </a>

                <a href="<?= Url::to(['/order/create', 'paymethod' => 'xj','order'=> $order, 'realestate' => $realestate, 'gateway' => '6']) ?>" title="现金">
                    <img src="\image\xj.png" class="img">
                </a>
            </div>
        </div>
    </div>

    <div class="QR">
        <img src="\images\QR_0_181006036731.png" id="qr" />
        <div class="word">
           支付二维码！<br />
        </div>


    </div>
</div>



