<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\WaterMeter;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WaterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '读数一览表';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-meter-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <?php
	    $gridColumn = [
	    	[ 'class' => 'kartik\grid\SerialColumn',
	    		'header' => '序<br />号'
	    	],

	    	[ 'attribute' => 'community',
			 //'value' => 'c.community_name',
			 'width' => '15%',
			],
	    	[ 'attribute' => 'building',
			 'width' => '60px',
			  'hAlign' => 'center', ],
	    	[ 'attribute' => 'name',
			 //'value' => 'r.room_name',
			 'width' => '70px',
			  'hAlign' => 'center', ],
	    	[ 'attribute' => 'year',
			 'width' => '70px',
			 'label' => '年份',
			  'hAlign' => 'center', ],
	    	[ 'attribute' => 'Jan', 
			 'mergeHeader' => true,
			 'label' => '一月',
			  'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Feb',
			 'mergeHeader' => true,
			 'label' => '二月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Mar',
			 'mergeHeader' => true,
			 'label' => '三月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Apr',
			 'mergeHeader' => true,
			 'label' => '四月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'May',
			 'mergeHeader' => true,
			 'label' => '五月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Jun',
			 'mergeHeader' => true,
			 'label' => '六月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Jul',
			 'mergeHeader' => true,
			 'label' => '七月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Aug',
			 'mergeHeader' => true,
			 'label' => '八月',
	    		'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Sept',
			 'mergeHeader' => true,
			 'label' => '九月',
			 'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Oct',
			 'mergeHeader' => true,
			 'label' => '十月',
			 'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'Nov',
			 'mergeHeader' => true,
			 'label' => '十一月',
			 'hAlign' => 'center',
	    	], 
			[ 'attribute' => 'D', 
			 'mergeHeader' => true,
			 'label' => '十二月',
			 'hAlign' => 'center',
	    	],
	    	
	    ];
	    echo GridView::widget( [
	    	'dataProvider' => $dataProvider,
	    	'filterModel' => $searchModel,
	    	'panel' => [ 'type' => 'info', 'heading' => '水表读数',
					   /*'before' => Html::a( '生成水费', [ 'y' ], [ 'class' => 'btn btn-success' ] )*/],
	    	'toolbar' => [
	    		'centent' => Html::a( '录入', [ 'new' ], [ 'class' => 'btn btn-success' ] )
	    	],
	    	'hover' => true,
	    	'columns' => $gridColumn,
	    ] );	
	?>
		
</div>
