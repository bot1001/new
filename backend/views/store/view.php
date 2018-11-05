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
</style>

<script>
    function image() {
        alert('你好');
    }

    function down() {
        var id = <?= $model['id'] ?>;
        alert(id);
    }
</script>

<div class="store-view">
    <?php
    $host = Yii::$app->request->hostInfo; //请求网址
    $url = $host.$model['cover'];
    $type = [ '1'=>'股份制', '2' => '个体经营户'];
    $certificate = ['0' => '否', '1' => '是'];
    //（状态,1启用,0禁用，2待审核，3锁定）
    $status = [ '0' => '禁用' , '1' => '启用', '2' => '待审核', '3' => '锁定']
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
            <p class="p">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['id']], ['class' => 'btn btn-primary', 'title' => '更新']) ?>
                <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                    'class' => 'btn btn-danger',
                    'onclick' => 'down()'
                ]) ?>
            </p>
        </div>

        <div class="right col-lg-8">
            <?= $model['introduce'] ?>
        </div>

    </div>

</div>
