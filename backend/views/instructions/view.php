<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Instructions */

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => 'Instructions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model['title'];
?>
<style>
    .instructions-view{
        width: 800px;
        border: solid 1px rgba(208, 225, 188, 0.78);
        border-radius: 15px;
        background: white;
    }
    .cont{
        width: 98%;
        background: white;
        margin: auto;
        border-radius: 5px;
        /*border: solid rgba(203, 203, 203, 0.53) 1px;*/
    }
    hr{
        border: solid darkseagreen 1px;
        width: 50%;
        text-align: left;
    }
    h1{
        padding: 1px;
        text-align: center;
    }
    #cont{
        background: rgba(128, 128, 128, 0.05);
        border-radius: 5px;
    }
    #p{
        float: right;
        position: relative;
        left: 60px;
    }
    .title{
        display: inline-flex;
        font-size: 20px;
    }
    .type{
        background: rgba(128, 128, 128, 0.44);
        border-radius: 5px;
        margin-right: 10px;
    }
</style>
<div class="instructions-view">
    <div class="cont">

        <h1><?= Html::encode($model['title']) ?></h1>
        <hr />
        <div class="title">
            <div class="type">
                <?= '作者：'.$model['name'] ?>
            </div>
            <div class="type">
                <?= '文章类型：'.\common\models\Instructions::arr($one = 'type')[$model['type']] ?>
            </div>

            <div class="type">
                <?= '文章类型：'.\common\models\Instructions::arr($one = 'status')[$model['status']] ?>
            </div>

            <div class="type">
                <?= '更新时间：'.date('Y-m-d H:i:s', $model['update_time']) ?>
            </div>

        </div>


        <div id="p">

             <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['id']], ['class' => 'btn btn-primary']) ?>
             <br />
             <br />
             <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['id']], [
                 'class' => 'btn btn-danger',
                 'data' => [
                     'confirm' => '您确定删除么?',
                     'method' => 'post',
                 ],
             ]) ?>

        </div>
        <div id="cont">
            <?= $model['content']; ?>
        </div>

    </div>
</div>
