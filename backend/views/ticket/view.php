<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

$this->title = $model->ticket_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-view container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
	echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ticket_id',
            'ticket_number',
	
	        ['attribute' => 'c.community_name',
			'label' => '小区'],
	
            'b.building_name',
		
            'r.room_name',
		
            'contact_person',
		
	        'contact_phone',
		
             ['attribute' => 'tickets_taxonomy',
			  'value' => function($model){
	            	$date = [1 => '建议', 2 => '投诉'];
	            	return $date[$model->tickets_taxonomy];
	            },
			 ],
		
            'explain1',
            'create_time:datetime',
            ['attribute' => 'is_attachment',
			'value' => function($model){
            	$d = [0=>'无', 1=> '无'];
            	return $d[$model->is_attachment];
            }],
            //'assignee_id',
            'reply_total',
            ['attribute' => 'ticket_status',
			 'value' => function($model){
	        	$d = [1=> '待接单',2 => '已接单',3 => '已完成',4 => '返修',5 => '关闭',6 => '处理中'];
	        	return $d[$model->ticket_status];
	        },],
            'remind',
        ],
    ]);
	?>
</div>
