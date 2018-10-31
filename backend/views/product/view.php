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
        width: 800px;
        border: solid 1px red;
        border-radius: 10px;
    }

    .p{
        position: relative;
        left: 50px;
        float: right;
    }

    .title{
        width: 400px;
        background: rgba(154, 205, 50, 0.27);
        min-height: 500px;
    }

    #title{
        font-size: 30px;
        margin-left: 10px;
    }

    .description{
        width: 95%;
        margin: auto;
    }

    #img{
        width: 50px;
    }

    th{
        text-align: right;
    }
</style>

<div class="product-view">

    <div class="title">
        <div id="title">
            <?= $model['name'] ?>
        </div>

        <div class="description">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    ['attribute' => 'header',
                        'label' => '副标题'],
                    ['attribute' => 'brand',
                        'label' => '品牌'],
                    ['attribute' => 'image',
                        'format' => ['image', ['height' => '30px']],
                        'value' => function($model){
                            return $model['image'];
                        },
                        'label' => '副标题'],
                ]
            ])
            ?>
        </div>
    </div>

        <div class="image">

        </div>


    <div class="description">
        <?php
        echo '<pre />';
        print_r($model);
        ?>
    </div>




    <!--    <div class="p">-->
    <!--        --><?php //= Html::a('更新', ['update', 'id' => $model['id']], ['class' => 'btn btn-primary']) ?>
    <!--        <br />-->
    <!--        <br />-->
    <!--        --><?php //= Html::a('删除', ['delete', 'id' => $model['id']], [
    //            'class' => 'btn btn-danger',
    //            'data' => [
    //                'confirm' => 'Are you sure you want to delete this item?',
    //                'method' => 'post',
    //            ],
    //        ]) ?>
    <!--    </div>-->

</div>
