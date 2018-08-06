<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceDelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '删除记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="invoice-del-index">

    <?php
    $gridview =[
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],
        ['attribute' => 'community',
            'value' => 'community.community_name',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $comm,
            'filterInputOptions' => ['placeholder' => '点击选择小区'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'contentOptions' => [ //内容居左
                    'class' => 'text-left',
            ],
            'label' => '小区',
            'width' => '210px'],

        ['attribute' => 'building',
            'value' => 'building.building_name',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $build,
            'filterInputOptions' => ['placeholder' => '...'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'label' => '楼宇',
            'width' => '100px'],

        ['attribute' => 'number',
            'value' => 'realestate.room_number',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $number,
            'filterInputOptions' => ['placeholder' => '...'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'label' => '单元'],

        ['attribute' => 'name',
            'value' => 'realestate.room_name',
            'label' => '房号'],

        ['attribute' => 'description',
            'contentOptions' => ['class' => 'text-left']],

        ['attribute' => 'year',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $y,
            'filterInputOptions' => ['placeholder' => '...'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],],

        ['attribute' => 'month',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $m,
            'filterInputOptions' => ['placeholder' => '...'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],],

        ['attribute' => 'amount',],

        ['attribute' => 'order_id',
            'value' => function($model){
                if(empty($model->order_id)){
                    return '';
                }else{
                    return $model->order_id;
                }
            }],

        ['attribute' => 'payment_time',
            'value' => function($model){
                if(empty($model->payment_time)){
                    return '';
                }else{
                    return date('Y-m-d H:i:s', $model->payment_time);
                }
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
                    'placeholder' => '请选择付款时间',
                    'style'=>'width:200px',
                ],
            ]],

        ['attribute' => 'user',
            'value' => 'user.name',
            'label' => '操作人'],
        ['attribute' => 'update_time',
            'value' => function($model){
                 return date('Y-m-d H:i:s', $model->update_time);
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
                    'placeholder' => '请选择操作时间',
                    'style'=>'width:200px',
                ],
            ],
        ],

        ['attribute' => 'invoice_notes',],

//        ['class' => 'kartik\grid\ActionColumn'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '删除记录'],
        'columns' => $gridview,
        'pjax' => true,
        'hover' => true,
    ]); ?>
</div>
