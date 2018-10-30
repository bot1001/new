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
    .p{
        position: relative;
        left: 50px;
        float: right;
    }
    .product-view{
        width: 800px;
        border: solid 1px red;
        border-radius: 10px;
        /*margin: auto;*/
    }
    .title{
        display: flex;
    }
    #title{
        height: 5px;
    }
    #img{
        width: 50px;
    }
</style>
<div class="product-view">

    <div class="p">
        <?= Html::a('更新', ['update', 'id' => $model['id']], ['class' => 'btn btn-primary']) ?>
        <br />
        <br />
        <?= Html::a('删除', ['delete', 'id' => $model['id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <div class="title">
        <div id="title">
            标题：<?= $model['name'] ?>
            副标题：<?= $model['header'] ?>
        </div>
        <div class="image">
            <img src="<?= $model['image'] ?>" id="img">
        </div>

    </div>

    <div class="title">

    </div>

    <?php
    echo '<pre />';
    print_r($model);
    ?>

</div>
