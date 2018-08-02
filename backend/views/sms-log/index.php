<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmsLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信发送记录';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>

<div class="sms-log-index">

    <?php

    $gridview = [
        ['class' => 'kartik\grid\SerialColumn','header' => '序<br />号'],

        ['attribute' => 'sign_name',
            'contentOptions' => ['class' => 'text-left'],],

        ['attribute' => 'sms',],

        ['attribute' => 'type',
            'value' => function($model){
                $date = ['0' => '验证码', '1' => '消息'];
                return $date[$model->type];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['0' => '验证码', '1' => '消息'],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
            'filterInputOptions' => [ 'placeholder' => '请选择' ],
            ],

        ['attribute' => 'count',
            'value' => function($model){
                return $model->count.'条';
            },],

        ['attribute' => 'success',
            'value' => function($model){
                return $model->success.'条';
            },],
        ['attribute' => 'phone',],

        ['attribute' => 'sender',
            'value' => 'sys.name',
            'contentOptions' => ['class' => 'text-left'],
            ],

        ['attribute' => 'sms_time',
            'value' => function($model){
                return date('Y-m-d H:i:s', $model->sms_time);
            },
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
            ],

        ['attribute' => 'property',
            'contentOptions' => ['class' => 'text-left'],],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '短信发送记录'],
        'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
