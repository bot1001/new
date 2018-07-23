<?php

use yii\helpers\Html;
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
        width: 90%;
        margin: auto;
        font-size: 16px;
    }
</style>
<div class="sms-client-index">
    <div id="box">
        <div id="div">
            <div id="title">模板列表</div>
            <?php foreach($sms as $s): $s =(object)$s ?>
            <div class="row" title="<?= $s->property;?>">
                <div class="col-lg-6"><?= $s->name; ?></div>
                <div class="col-lg-6"><?= $s->sms; ?></div>
            </div>

            <?php endforeach; ?>
        </div>

    </div>
</div>
