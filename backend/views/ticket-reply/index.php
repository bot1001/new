<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TicketReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务回复';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="ticket-reply-index">

    <?php
	$grid = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

		    ['attribute' => 'ticket_id',
			 'format' => 'raw',
			 'value' => function($model){
	        	$url = Yii::$app->urlManager->createUrl(['/ticket/index', 'ticket_id' => $model->ticket_id]);
	        	return Html::a($model->ticket_id, $url);
	        },
                'contentOptions' => ['class' => 'text-left'],],
		
            ['attribute' => 'name',
			'value' => 'd.real_name',
                'contentOptions' => ['class' => 'text-left'],
			'label' => '回复人',],

        ['attribute' => 'content',
            'contentOptions' => ['class' => 'text-left'],
			'value' => 'E'],
		
            ['attribute' => 'is_attachment',
			'value' => function($model){
	        	$d = [0 => '无', 1 => '有', 2 => '其他'];
	        	return $d[$model->is_attachment];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
	         'filter' => [0 => '无', 1 => '有', 2 => '其他'],
	         'filterInputOptions' => [ 'placeholder' => '请选择' ],
	         'filterWidgetOptions' => [
	         	'pluginOptions' => [ 'allowClear' => true ],
	         ],
                ],
		
            ['attribute' => 'reply_time',
	        		'value' => function($model){
	        	return date('Y-m-d H:m:s', $model->reply_time);
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
                    //'style'=>'width:200px',
                ],
		    ],
                ],
		
            ['attribute' => 'reply_status',
			'value' => function($model){
	        	$d = [1 => '正常', 2 => '删除'];
	        	return $d[$model->reply_status];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
	         'filter' => [1 => '正常', 2 => '删除'],
	         'filterInputOptions' => [ 'placeholder' => '请选择' ],
	         'filterWidgetOptions' => [
	         	'pluginOptions' => [ 'allowClear' => true ],
	         ],
			 'class' => 'kartik\grid\EditableColumn',
		     'editableOptions' => [
		         'formOptions' => [ 'action' => [ '/ticket-reply/reply' ] ],
		         'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
		         'data' => [1 => '正常', 2 => '删除'],
		     ],
                ],
		    	    
            ['class' => 'kartik\grid\ActionColumn',
                'template' => Helper::filterActionColumn('{update}{view}{delete}'),
			'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '服务回复'],
		'hover' => true,
        'columns' => $grid,
    ]); ?>
</div>
