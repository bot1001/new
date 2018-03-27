<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InformationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informations';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="information-index">

    <?php
	
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn','header' => '序<br />号'],

            //'remind_id',
            ['attribute' => 'room_name','mergeHeader' => true],
		
            ['attribute' => 'detail',],
		
            ['attribute' => 'times',
			 'label' => '提醒次数/次',
			 'hAlign' => 'center',
			],
		
            ['attribute' => 'reading','hAlign' => 'center'],
		
            ['attribute' => 'target',],
		
            ['attribute' => 'ticket',
			'value' => 'ticket.ticket_number',
			 'hAlign' => 'center',
			'label' => '订单编号'],
		
            ['attribute' => 'remind_time',
			'value' => function(){
	        	return date('Y-m-d H:i:',$model->remind_time);
	        },'hAlign' => 'center'],
		
            ['attribute' => 'property',],
		

            ['class' => 'kartik\grid\ActionColumn','header' => '操<br />作'],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridview,
    ]); ?>
</div>
