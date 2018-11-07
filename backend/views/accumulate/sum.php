<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccumulateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户积分';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
    .store-accumulate-index{
        max-width: 800px;
    }
    #name, #from, #end{
        border: solid 1px gray;
        border-radius: 4px;
        width: 70px;
    }
    #name{
        width: 80px;
    }
    #submit{
        border-radius: 5px;
    }
</style>
<div class="store-accumulate-index" >

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序号'],

        ['attribute' => 'name',
            'format' => 'raw',
            'value' => function($model){
                $url = Url::toRoute(['index', 'account_id' => $model['id']]);
                return Html::a($model['name'], $url);
            },
            'contentOptions' => ['class' => 'text-left'],
            'label' => '姓名'],

        ['attribute' => 'amount',
            'format' => 'raw',
            'value' => function($model){
                $url = Url::toRoute(['index', 'account_id' => $model['id']]);
                return Html::a($model['amount'], $url);
            },
            'contentOptions' => ['class' => 'text-right'],
            'label' => '积分'
            ],

        ['attribute' => 'time',
            'value' => function(){
                return date('Y-d H:i:s');
            },
            'mergeHeader' => true,
            'label' => '统计时间'],

        ['class' => 'kartik\grid\CheckboxColumn'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => ['type' => 'info', 'heading' => '总积分：'.$amount,
            'before' => "<form action= '/accumulate/accumulate' method='get'>
                               <input id='name' name='name' value= '$name' onkeyup=\"value=value.replace(/[^\u4E00-\u9FA5]/g,'')\" placeholder='姓名'>
                               <input id='from' type='number' name='from' value='$from' oninput=\"this.value=this.value.replace(/[^0-9-]+/,'');\" placeholder='积分起'>
                               <input id='end' type='number' name='to' value='$to' oninput=\"this.value=this.value.replace(/[^0-9-]+/,'');\"  placeholder='积分止'>
                               <input id='submit' type='submit' value='搜索'>
                           </form>"],
        'columns' => $gridview,
        'hover' => true,
        'pjax' => true
    ]); ?>
</div>
