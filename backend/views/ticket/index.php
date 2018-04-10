<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

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

            ['attribute' => 'ticket_number',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'community_id',
			 'value' => 'c.community_name',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'building',
			 'value' => 'b.building_name',
			 'label' => '楼宇',
			'width' => 'px',
			'hAlign' => 'center'],
		
		   ['attribute' => 'name',
			'value' => 'r.room_name',
			'label' => '房号',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'account_id',
			 'value' => 'ac.real_name',
			'width' => 'px',
			'hAlign' => 'center'],
		
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
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'explain1',
			 'value' => 'E',
			'width' => '5%',
			],
		    
            ['attribute' => 'create_time',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'contact_person',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'contact_phone',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            /*['attribute' => 'is_attachment',
			'width' => 'px',
			'hAlign' => 'center'],*/
		    		   		    
            ['attribute' => 'ticket_status',
			 'value' => function($model){
	        	$d = [0 => '关闭',1=> '待接单',2 => '已接单',3 => '已完成',4 => '返修',5 => '关闭',6 => '处理中'];
	        	return $d[$model->ticket_status];
	        },
			'width' => 'px',
			'hAlign' => 'center'],
		    
            /*['attribute' => 'remind',
			'width' => 'px',
			'hAlign' => 'center'],*/
		    

            ['class' => 'kartik\grid\ActionColumn', 'width' => '80px']
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
