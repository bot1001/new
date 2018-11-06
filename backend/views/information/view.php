<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Information */

$this->title = $model['id'];
$this->params['breadcrumbs'][] = ['label' => 'Informations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .information-view{
        width: 500px;
    }
    #information{
        text-align: center;
        margin-top: 10px;
    }
    #information_view{
        background: rgba(154, 205, 50, 0.27);
        width: 100%;
        text-align: center;
        border-radius: 10px;
    }
    .table{
        margin: auto;
        width: 95%;
        tr:hover
    }
    .table tr, .table td{
        border: solid 2px white;
    }
    th{
        width: 100px;
        text-align: right;
    }
    tr{
        text-align: left;
    }
    tr:hover{
        background: rgba(128, 128, 128, 0.25);
    }

    .i_detail{
        max-height: 250px;
    }
</style>

<div class="information-view">

    <?php
    if($type == '2')
    {
        $name = '店名';
    }else{
        $name = '小区';
    }
    $reading = ['未读', '已读'];
    $id = $model['id'];
    $url = '/product/view';
    ?>

    <script>
        function del() {
            if(confirm('您确定要删除吗')){
                var xhr = new XMLHttpRequest();
                xhr.open('get', 'delete/?id='+<?= $id ?>)
                xhr.onload = function () {
                    var text = xhr.responseText;
                    if (text == '1'){
                        alert('删除成功');
                    }else{
                        alert('删除失败');
                    }
                }
                xhr.send();
            }
        }
    </script>

    <div id="information_view">
        <table class="table">
            <tr>
                <th><?= $name ?></th>
                <td><?= $model['name'] ?></td>
            </tr>

            <tr class="i_detail">
                <th class="i_title">详情</th>
                <td><?= $model['detail'] ?></td>
            </tr>

            <?php if($type == '1'){ ?>
                <tr>
                    <th>提醒次数</th>
                    <td><?= $model['time'] ?></td>
                </tr>
            <?php } ?>

            <tr>
                <th>对应单号</th>
                <td><?= Html::a($model['number'], [$url, 'id' => $model['number']], [ 'title' => '点击查看' ]) ?></td>
                <?= $model['number'] ?>
            </tr>

            <tr>
                <th>时间</th>
                <td><?= $model['time'] ?></td>
            </tr>

             <tr>
                <th>状态</th>
                <td><?= $reading[$model['reading']] ?></td>
             </tr>

            <tr>
                <th>备注</th>
                <td><?= $model['property'] ?></td>
            </tr>


        </table>
    </div>

    <p id="information">
        <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
            'class' => 'btn btn-danger',
            'onclick' => "del($id)"
        ]) ?>
        <?= Html::a('<span class="glyphicon glyphicon-check"></span>', ["$url", 'id' => $model['number']],
            ['class' => 'btn btn-primary', 'title' => '点击查看' ]) ?>
    </p>

</div>
