<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .product-view{
        border-radius: 10px;
        margin-left: 0px;
    }

    .p{
        position: relative;
        top: -10px;
        text-align: center;
    }

    .title{
        width: 300px;
        background: rgba(154, 205, 50, 0.27);
        border-radius: 10px;
        margin-right: 10px;
    }

    #title{
        font-size: 30px;
        margin-left: 10px;
    }

    .table{
        width: 300px;
        font-size: 15px;
    }
    th, td{
        border: solid 1px white;
    }

    #img{
        width: 120px;
    }

    th{
        text-align: right;
    }

    .description{
        min-width: 500px;
        height: 800px;
        background: white;
        border-radius: 10px;
        overflow-y: auto;
    }
</style>

<div class="product-view row">

    <div class="title col-lg-1">
            <span id="title">
                <?= $model['name'] ?>
            </span>
            <table class="table">
                <tbody>
                <tr>
                    <th>副标题</th>
                    <td><?= $model['name'] ?></td>
                </tr>
                <tr>
                    <th>品牌</th>
                    <td><?= $model['brand'] ?></td>
                </tr>
                <tr>
                    <th>系列</th>
                    <td><?= $model['taxonomy'] ?></td>
                </tr>

                <tr>
                    <th>市场价</th>
                    <td><?= $model['price'] ?></td>
                </tr>

                <tr>
                    <th>优惠金额</th>
                    <td><?= $model['sale'] ?></td>
                </tr>

                <tr>
                    <th>积分抵现</th>
                    <td><?= $model['accumulate'] ?></td>
                </tr>

                <tr>
                    <th>状态</th>
                    <td><?= $model['status'] ?></td>
                </tr>

                <tr>
                    <th>创建时间</th>
                    <td><?= $model['create_time'] ?></td>
                </tr>

                <tr>
                    <th>修改时间</th>
                    <td><?= $model['update_time'] ?></td>
                </tr>

                <tr>
                    <th>缩略图</th>
                    <td><img src="<?= $model['image'] ?>" id="img"></td>
                </tr>
                </tbody>
            </table>
            <div class="p">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                    ['update', 'id' => $model['id']], ['class' => 'btn btn-primary', 'title'=>'更新']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    ['#', 'id' => $model['id']], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '您确定要下架此商品吗？',
                        'method' => 'post',
                    ],
                    'title'=>'删除',
                ]) ?>
            </div>
    </div>

    <div class="description col-lg-6">
        <div id="description">
            <?= $model['introduction'] ?>
        </div>
    </div>

</div>
