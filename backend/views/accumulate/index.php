<?php

use yii\helpers\Html;
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
</style>
<div class="store-accumulate-index" >

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],

        ['attribute' => 'name',
            'value' => 'data.real_name',
            'contentOptions' => ['class' => 'text-left'],
            'label' => '姓名'],

        ['attribute' => 'phone',
            'value' => 'account.mobile_phone',
            'label' => '手机号码'],

        ['attribute' => 'amount',
            'contentOptions' => ['class' => 'text-right'],],

        ['attribute' => 'type',
            'value' => function($model){
                $date = ['1' => '物业', '2' => '商城'];
                return $date[$model->type];
            }],
        ['attribute' => 'update_time',
            'filterType' =>GridView::FILTER_DATE_RANGE,//'\kartik\daterange\DateRangePicker',//过滤的插件，
            'filterWidgetOptions'=>[
                'pluginOptions'=>[
                    'autoUpdateOnInit'=>false,
                    //'showWeekNumbers' => false,
                    'useWithAddon'=>true,
                    'convertFormat'=>true,
                    'timePicker'=>false,
                    'locale'=>[
                        'format' => 'YYYY-MM-DD',
                        'separator'=>' to ',
                        'applyLabel' => '确定',
                        'cancelLabel' => '取消',
                        'fromLabel' => '起始时间',
                        'toLabel' => '结束时间',
                        //'daysOfWeek'=>false,
                    ],
                    'opens'=>'center',
                    //起止时间的最大间隔
                    'dateLimit' =>[
                        'days' => 90
                    ]
                ],
                'options' => [
                    'placeholder' => '请选择...',
                    'style'=>'width:200px',
                ],
            ],
        ] ,

        ['attribute' => 'property',
            'contentOptions' => ['class' => 'text-right'],],


//        ['class' => 'kartik\grid\ActionColumn',
//            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '用户积分列表'],
        'columns' => $gridview,
    ]); ?>
</div>
