<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">服务回复</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$v_Url = Url::toRoute( ['/ticket-reply/view'] );

$cJs = <<<JS
    $('.cr').on('click', function () {
        $.get('{$v_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\TicketReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务回复';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-reply-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Ticket Reply', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
	$grid = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

            ['attribute' => 'name',
			'value' => 'd.real_name',
			'label' => '回复人',
			'hAlign' => 'center'],
		
            //'account_id',
            ['attribute' => 'content',
			'value' => 'E'],
		
            ['attribute' => 'is_attachment',
			'value' => function($model){
	        	$d = [0 => '无', 1 => '有'];
	        	return $d[$model->is_attachment];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
	         'filter' => [0 => '无', 1 => '有'],
	         'filterInputOptions' => [ 'placeholder' => '请选择' ],
	         'filterWidgetOptions' => [
	         	'pluginOptions' => [ 'allowClear' => true ],
	         ],
			'hAlign' => 'center'],
		
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
			'hAlign' => 'center'],
		
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
			 'hAlign' => 'center'],
		
		    ['attribute' => 'ticket_id',
		    	 'format' => 'raw',
		    	 'value' => function($model){
	            	return Html::a('More', '#', [
	            				'data-toggle' => 'modal',
	            				'data-target' => '#view-modal',
	            				'class' => 'cr',
	            			] );
	            },
		    	 'mergeHeader' => true,
		    	 'label' => '更多', 
		    	 'hAlign' => 'center'],
		    
            /*['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br />作'],*/
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '服务回复'],
		'hover' => true,
        'columns' => $grid,
    ]); ?>
</div>
