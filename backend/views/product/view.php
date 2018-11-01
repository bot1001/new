<?php

use yii\helpers\Html;
use yii\helpers\Url;

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

    th, td{
        border: solid 1px white;
    }

    th{
        width: 80px;
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

<script>
    function down(status) {
        var p_message = '';
        if (status == '2'){
            p_message = '您确定要下架此宝贝吗？'
        }else if(status == '3')
        {
            p_message = '你确定要申请上架吗？';
        }
        if(confirm(p_message)){
            var xhr = new XMLHttpRequest(); //实例化ajax
            xhr.open('get', "/product/down?id=<?= $model['id'] ?>&status="+status, true); //请求路由
            xhr.onload = function () {
                var text = this.responseText;
                if( text == '1'){
                    alert('操成功!');
                    window.location.reload();
                }
            }
            xhr.send(); //发送请求
        }
    }
</script>

<div class="product-view row">

    <div class="title col-lg-1">
            <span id="title">
                <?= $model['name'] ?>
            </span>
            <table class="table">
                <tbody>
                <tr>
                    <th>副标题</th>
                    <td><?= $model['header'] ?></td>
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
                    <td><?= $status[$model['status']] ?></td>
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

                <?php
                if($model['status'] == '2'){
                    echo Html::a('<span class="glyphicon glyphicon-chevron-up"></span>', '#',
                        [
                            'class' => 'btn btn-info',
                            'onclick' => "down( '3' )"
                        ]);
                }else{
                    echo Html::a('<span class="glyphicon glyphicon-chevron-down"></span>', '#',
                        [
                            'class' => 'btn btn-danger',
                            'onclick' => 'down(2)'
                        ]);
                }
                 ?>
            </div>
    </div>

    <div class="description col-lg-6">
        <div id="description">
            <?= $model['introduction'] ?>
        </div>
    </div>
</div>
