<?php

use yii\helpers\Html;
use kartik\grid\GridView;

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
		    
            ['attribute' => 'account_id',
			 'value' => 'ac.real_name',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'community_id',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'realestate_id',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'tickets_taxonomy',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'explain1',
			'width' => '5%',
			'hAlign' => 'center'],
		    
            ['attribute' => 'create_time',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'contact_person',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'contact_phone',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'is_attachment',
			'width' => 'px',
			'hAlign' => 'center'],
		    		   		    
            ['attribute' => 'ticket_status',
			'width' => 'px',
			'hAlign' => 'center'],
		    
            ['attribute' => 'remind',
			'width' => 'px',
			'hAlign' => 'center'],
		    

            ['class' => 'kartik\grid\ActionColumn']];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '投诉/建议',
				   'before'=> Html::a('New', ['/tickect/create'], ['class' => 'btn btn-primary'])],
        'columns' => $gridview,
    ]); ?>
</div>
