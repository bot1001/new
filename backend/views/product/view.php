<?php

use yii\helpers\Html;
use yii\helpers\Url;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render(Yii::$app->params['modal']);
?>
<style>
    .product-view{
        border-radius: 10px;
        margin-left: 0px;
    }

    .p, .p_property, .add, .v_check{
        margin-bottom: 5px;
    }

    .title{
        width: 400px;
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
        text-align: right;
    }

    #icon{
        height: 60px;
        width: 60px;
        line-height: 60px;
    }

    #img{
        height: 60px;
        width: 60px;
        border-radius: 15px;
    }

    .description{
        min-width: 500px;
        height: 800px;
        background: white;
        border-radius: 10px;
        overflow-y: auto;
    }

    .p_property{
        background: white;
        border-radius: 5px;
    }
    .add{
        background: white;
        border-radius: 5px;
        display: none;
    }

    .v_check{
        display: none;
        margin: auto;
        width: 370px;
        text-align: center;
    }
    .pass, .fail{
        margin-bottom: 5px;
        border-radius: 3px;
    }
    .pass{
        background: rgba(0, 128, 0, 0.76);
    }
    .fail{
        background: rgba(255, 69, 0, 0.77);
    }
</style>

<script>
    function down(status) {
        var p_message = '';
        // alert(status);
        if (<?= $model['status'] ?> == '5' || <?= $model['status'] ?> == '2'){
            p_message = '你确定要申请上架吗？';
        }else{
            p_message = '您确定要下架此宝贝吗？'
        }

        if(confirm(p_message)){
            var xhr = new XMLHttpRequest(); //实例化ajax
            xhr.open('get', "/product/down?id=<?= $model['id'] ?>&status="+status, true); //请求路由
            xhr.onload = function () {
                var text = this.responseText;
                if( text == '1'){
                    alert('操成功!');
                    window.location.reload();
                }else {
                    alert('操作失败');
                }
            }
            xhr.send(); //发送请求
        }
    }

    $(document).ready(function(){ //点击显示或隐藏输入框
        $("#v_new").click(function(){
            $("#add").toggle(500);
        });
    });

    $(document).ready(function(){ //点击显示或隐藏输入框
        $("#check_b").click(function(){
            $("#v_check").toggle(500);
        });
    });

    function check(value) {
        if(value == '1'){
            var message = '您确定通过审核吗';
        }else{
            var message = '你确定要拒绝吗';
        }

        if(confirm(message)){
            var xhr = new XMLHttpRequest();
            xhr.open('get', "check?id=<?= $model['id'] ?>"+"&status="+value+"&store=<?= $model['store_id'] ?>", true);
            xhr.onload = function () {
                var text = this.responseText;
                if( text == '1'){
                    alert('操成功!');
                    window.location.reload();
                }else {
                    alert('操作失败');
                }
            }
            xhr.send();
        }
    }
</script>

<div class="product-view row">

    <div class="title col-lg-3">

        <span id="title"><?= $model['name'] ?></span>

        <table class="table">
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
                <th id="icon">缩略图</th>
                <td>
                     <?php
                    $url = $model['image'];

                    echo Html::a("<img src='$url', id = 'img' />", "#",
                        [
                                'class' => 'pay',
                            'data-toggle' => 'modal',
                            'data-url' => Url::toRoute(['img', 'id' => $model['id'], 'image' => $url, 'type' => 'product']),
                            'data-title' => '修改缩略图',
                            'data-target' => '#common-modal'
                        ])
                     ?>
                </td>
            </tr>
        </table>

        <div class="p_property"><?= $this->render('property.php', ['data' => $data]) ?></div>
        <div class="add" id="add"><?= $this->render('add.php', ['id' => $model['id']]) ?></div>
        <div class="v_check row" id="v_check" >
            <div class="pass btn col-lg-6" onclick="check('1')">通过</div>
            <div class="fail btn col-lg-6" onclick="check('5')">拒绝</div>
        </div>

        <div class="p" style="text-align: center">
            <?= Html::button('<span class="glyphicon glyphicon-plus"></span>',
                ['class' => 'btn btn-default', 'id' => 'v_new', 'title'=>'添加属性']); ?>

            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                ['update', 'id' => $model['id']], ['class' => 'btn btn-primary', 'title'=>'更新']) ?>

            <?php
            if($model['status'] == '2' || $model['status'] == '5'){
                echo Html::a('<span class="glyphicon glyphicon-chevron-up"></span>', '#',
                    ['class' => 'btn btn-info',
                        'title' => '更新状态',
                        'onclick' => "down( '3' )"
                    ]);
            }else{
                echo Html::a('<span class="glyphicon glyphicon-chevron-down"></span>', '#',
                    ['class' => 'btn btn-warning',
                        'title' => '更新状态',
                        'onclick' => 'down(2)'
                    ]);
            } ?>

            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:void(0)',
                ['class' => 'btn btn-danger trash','title' => '删除属性',]); ?>

            <?php
            if(Helper::checkRoute('check') && $model['status'] == '3'){
                echo Html::button('OK', ['class' => 'btn btn-success', 'id' => 'check_b', 'title'=>'审核']);
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
