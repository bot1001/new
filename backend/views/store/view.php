<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = '我的店铺';
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render(Yii::$app->params['modal']);
?>
<style>
    .store-view, .detail .right{
        border-radius: 10px;
    }
    .store-view .detail{
        position: relative;
        left: 15px;
    }

    .store-view .detail .top{
        font-size: 30px;
        text-align: left;
    }

    .store-view .detail .left{
        border-radius: 10px;
        margin-right: 10px;
        width: 500px;
        background: rgba(154, 205, 50, 0.27);
    }

    .table th{
        width: 100px;
        border: solid 1px white;
        text-align: right;
    }

    .table td{
        border: solid 1px white;
        text-align: left;
    }

    .p{
        margin: auto;
        text-align: center;
        position: relative;
        top: -10px;
    }
    .detail .right{
        min-height: 500px;
        max-width: 800px;
        background: rgba(255, 255, 224, 0.53);
    }

    #img{
        height: 60px;
        width: 60px;
        border-radius: 15px;
    }
    #s_cover{
        line-height: 60px;
    }
    #s_check{
        display: none;
        text-align: center;
        margin: auto;
        position: relative;
        top: -15px;
    }
    .s_check{
        display: none;
    }

    #c_pass, #c_fail{
        height: 35px;
        width: 30%;
        border-radius: 5px;
        font-size: 25px;
        color: rgba(255, 255, 255, 0.56);padding: 0px;
    }
    #c_pass{
        background: green;
    }
    #c_fail{
        position: relative;
        left: 10px;
        background: orangered;
    }
</style>

<script>
    function check(status) {

        var message = ''
        if(status == '1'){
            message = '您确定通过申请吗'
        }else if(status == '0'){
            message = '您确定要关闭店铺吗？';
        }else{
            message = '您确定拒绝申请吗'
        }
        if(confirm(message)){
            var id = <?= $model['id'] ?>;
            var xhr = new XMLHttpRequest();//实例化请求
            xhr.open('get', "/store/check?id="+id+"&status="+status, true);
            xhr.onload = function () {
                var text = this.responseText;
                if (text == '1'){
                    alert('操作成功');
                    location.reload();
                }else{
                    alert('操作失败');
                }
            }
            xhr.send();
        }
    }

    function down(status) {

        var message = ''
        if(status == '1'){
            message = '您确定通过申请吗'
        }else if(status == '0'){
            message = '您确定要关闭店铺吗？';
        }else{
            message = '您确定拒绝申请吗'
        }
        if(confirm(message)){
            var id = <?= $model['id'] ?>;
            var xhr = new XMLHttpRequest();//实例化请求
            xhr.open('get', "/store/down?id="+id+"&status="+status, true);
            xhr.onload = function () {
                var text = this.responseText;
                if (text == '1'){
                    alert('操作成功');
                    location.reload();
                }else{
                    alert('操作失败');
                }
            }
            xhr.send();
        }
    }

    $(document).ready(function(){ //点击显示或隐藏输入框
        $("#store_check").click(function(){
            $("#s_check").toggle(500);
        });
    });
</script>

<div class="store-view">
    <?php
    $host = Yii::$app->request->hostInfo; //请求网址
    $url = $host.$model['cover'];
    $type = [ '1'=>'股份制', '2' => '个体经营户'];
    $certificate = ['0' => '否', '1' => '是'];
    //（状态,1启用,0禁用，2待审核，3锁定）
    $status = Yii::$app->params['store']['status'];
    ?>
    <div class="detail row">
        <div class="left col-lg-4">
            <p class="top">我的店铺</p>
            <table class="table">
                <tr>
                    <th>店名</th>
                    <td colspan="2"><?= $model['name'] ?></td>
                </tr>

                <tr >
                    <th>法人</th>
                    <td><?= $model['person'] ?></td>
                    <td colspan="2" rowspan="2"><?= Html::a("<img src='$url', id = 'img' />", "#",
                            [
                                'class' => 'pay',
                                'data-toggle' => 'modal',
                                'data-url' => Url::toRoute(['img', 'id' => $model['id'], 'image' => $url]),
                                'data-title' => '修改缩略图',
                                'data-target' => '#common-modal'
                            ]
                        ) ?></td>
                </tr>

                <tr>
                    <th>联系号码</th>
                    <td><?= $model['phone'] ?></td>
                </tr>

                <tr>
                    <th>营业执照</th>
                    <td colspan="2"><?= $model['code'] ?></td>
                </tr>

                <tr>
                    <th>省份</th>
                    <td colspan="2"><?= $model['province'] ?></td>
                </tr>

                <tr>
                    <th>城市</th>
                    <td colspan="2"><?= $model['city'] ?></td>
                </tr>

                <tr>
                    <th>详细地址</th>
                    <td colspan="2"><?= $model['address'] ?></td>
                </tr>

                <tr>
                    <th>行业</th>
                    <td colspan="2"><?= $model['taxonomy'] ?></td>
                </tr>

                <tr>
                    <th>公司类型</th>
                    <td colspan="2"><?=$type[$model['type']] ?></td>
                </tr>

                <tr>
                    <th>公司规模</th>
                    <td colspan="2"><?= $model['people'] ?></td>
                </tr>

                <tr>
                    <th>是否认证</th>
                    <td colspan="2"><?= $certificate[$model['certificate']]; ?> </td>
                </tr>

                <tr>
                    <th>排序</th>
                    <td colspan="2"><?= $model['sort'] ?></td>
                </tr>

                <tr>
                    <th>创店时间</th>
                    <td colspan="2"><?= $model['time'] ?></td>
                </tr>

                <tr>
                    <th>状态</th>
                    <td colspan="2"><?= $status[$model['status']] ?></td>
                </tr>

            </table>

            <div id="s_check" class="s_check">
                <div id="c_pass" class="btn" onclick="check(1)">通过</div>
                <div id="c_fail" class="btn" onclick="check(0)">拒绝</div>
            </div>

            <p class="p">
                <?php
                $role = Yii::$app->user->identity;
                $role = $role->salt;

                if($role == '2'){
                    echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['id']],
                        ['class' => 'btn btn-primary', 'title' => '更新']);
                }
                ?>

                <?php
                if($model['status'] != '0'){
                    echo Html::button('<span class="glyphicon glyphicon-eye-close"></span>', [
                        'class' => 'btn btn-danger',
                        'onclick' => 'down(0)'
                    ]);
                }
                ?>

                <?php
                $url = '/store/check';
                $role = \app\models\Limit::limit($url);

                if($model['status'] == '2' && $role == '1'){
                    echo Html::button('<span class="glyphicon glyphicon-check"></span>', [
                        'class' => 'btn btn-info',
                        'id' => 'store_check'
                    ]);
                } ?>
            </p>
        </div>

        <div class="right col-lg-8">
            <?= $model['introduce'] ?>
        </div>

    </div>

</div>
