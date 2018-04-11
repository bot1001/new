<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use mdm\admin\components\Helper;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">投诉/建议</h4>',
    'options'=>[
    	'data-backdrop' => 'static','data-keyboard'=> true,
    ],
    ] );
$n_Url = Url::toRoute( '/ticket/create');

$nJs = <<<JS
   $('.new').on('click',function(){
      $('.modal-title').html('新建');
      $.get('{$n_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs ($nJs);

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '投诉/建议';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-index">

    <?php
	$gridview = [
           // ['class' => 'kartik\grid\SerialColumn'],

            ['attribute' => 'ticket_id',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'community_id',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $comm,
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			 'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'width' => '10%'],
		    
            ['attribute' => 'building',
			 'value' => 'b.building_name',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $build,
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			 'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			 'label' => '楼宇',
			'width' => '5%',
			'hAlign' => 'center'],
		
		   ['attribute' => 'name',
			'value' => 'r.room_name',
			'label' => '房号',
			'width' => 'px',
			'hAlign' => 'center'],
		
		   ['attribute' => 'contact_person',
			'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( strlen($model->account_id) > 16 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/ticket/ticket	' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center'],
		    
           ['attribute' => 'contact_phone',
			'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( strlen($model->account_id) > 16 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/ticket/ticket	' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'width' => 'px',
			'hAlign' => 'center'],
		    
            /*['attribute' => 'account_id',
			 'value' => 'ac.real_name',
			'width' => 'px',
			'hAlign' => 'center'],*/
		
		    /*['attribute' => 'r.building_id',
			'width' => 'px',
			'hAlign' => 'center'],
		
		    ['attribute' => 'r.building_id',
			'width' => 'px',
			'hAlign' => 'center'],*/
		    
            ['attribute' => 'tickets_taxonomy',
			 'value' => function($model){
	        	$date = [1 => '建议', 2 => '投诉'];
	        	return $date[$model->tickets_taxonomy];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => [1 => '建议', 2 => '投诉'],
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			 'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'explain1',
			 'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( strlen($model->account_id) > 16 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/ticket/ticket	' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXTAREA,
			],
			 'value' => 'E',
			'width' => '5%',
			],
		    
            ['attribute' => 'create_time',
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
		    ]],
		
            /*['attribute' => 'is_attachment',
			'width' => 'px',
			'hAlign' => 'center'],*/
		    		   		    
            ['attribute' => 'ticket_status',
			 'value' => function($model){
	        	$d = [1=> '待接单',2 => '已接单',3 => '已完成',4 => '返修',5 => '关闭',6 => '处理中'];
	        	return $d[$model->ticket_status];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => [1=> '待接单',2 => '已接单',3 => '已完成',6 => '处理中', 4 => '返修', 5 => '关闭'],
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			 'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			 'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( strlen($model->account_id) > 16 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/ticket/ticket	' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
		        'data' => [1=> '待接单',2 => '已接单',3 => '已完成',6 => '处理中', 4 => '返修', 5 => '关闭'],
			],
			'hAlign' => 'center'],
		    
            /*['attribute' => 'remind',
			'width' => 'px',
			'hAlign' => 'center'],*/
		    

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
			 'width' => '80px']
	];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '投诉/建议',
				   'before' => Html::a( 'New', '#', [
			        	'data-toggle' => 'modal',
			        	'data-target' => '#update-modal', 
		                'class' => 'btn btn-success new',
			        ] )],
        'columns' => $gridview,
    ]); ?>
</div>
