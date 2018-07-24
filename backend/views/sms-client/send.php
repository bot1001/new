<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmsClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信业务';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    #box{
        height: 300px;
        width: 400px;
        background: #FFFFFF;
        border-radius:15px;
    }
    #div{
        position: relative;
        /*padding: 5px;*/
        top: 3%;
        left: 3%;
        width: 94%;
        height: 94%;
        border-radius:10px;
    }
    #title{
        text-align: center;
        font-size: 18px;
    }
    .row{
        background: #0DE842;
        border-radius:5px;
        width: 95%;
        margin: auto;
        font-size: 16px;
    }
</style>
<div class="sms-client-index">
    <div id="box">
        <div id="div">
            <div id="title">模板列表</div>
            <?php foreach($sms as $s): $s =(object)$s ?>
            <a href="<?= Url::to(['/sms-client/send01/', 'sms' => $s->sms, 'name' => $s->name]) ?>">
                <div class="row" title="<?= $s->property;?>">
                    <div class="col-lg-3"><?= $s->name; ?></div>
                    <div class="col-lg-4"><?= $s->sms; ?></div>
                    <div class="col-lg-5" style="text-align: right"><?= $s->property; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>
