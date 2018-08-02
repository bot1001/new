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

<style>
    th{
        text-align: center;
    }

    td{
        text-align: right;
    }
</style>
<div class="water-meter-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <?php
	    $gridColumn = [
	    	[ 'class' => 'kartik\grid\SerialColumn',
	    		'header' => '序<br />号'
	    	],

	    	[ 'attribute' => 'community',
                'contentOptions' => ['class' => 'text-left'],
			 //'value' => 'c.community_name',
			 'width' => '15%',
			],
	    	[ 'attribute' => 'building',
                'contentOptions' => ['class' => 'text-center'],
			 'width' => '60px', ],

	    	[ 'attribute' => 'name',
                'contentOptions' => ['class' => 'text-center'],
			 //'value' => 'r.room_name',
			 'width' => '70px', ],

	    	[ 'attribute' => 'year',
                'contentOptions' => ['class' => 'text-center'],
			 'width' => '70px',
			 'label' => '年份',],

	    	[ 'attribute' => 'Jan', 
			 'mergeHeader' => true,
			 'label' => '一月',
	    	],

			[ 'attribute' => 'Feb',
			 'mergeHeader' => true,
			 'label' => '二月',
            ],

			[ 'attribute' => 'Mar',
			 'mergeHeader' => true,
			 'label' => '三月',
	    	],

			[ 'attribute' => 'Apr',
			 'mergeHeader' => true,
			 'label' => '四月',
	    	],
			[ 'attribute' => 'May',
			 'mergeHeader' => true,
			 'label' => '五月',
	    	],

			[ 'attribute' => 'Jun',
			 'mergeHeader' => true,
			 'label' => '六月',
	    	],

			[ 'attribute' => 'Jul',
			 'mergeHeader' => true,
			 'label' => '七月',
	    	],

			[ 'attribute' => 'Aug',
			 'mergeHeader' => true,
			 'label' => '八月',
	    	],

			[ 'attribute' => 'Sept',
			 'mergeHeader' => true,
			 'label' => '九月',
	    	],

			[ 'attribute' => 'Oct',
			 'mergeHeader' => true,
			 'label' => '十月',
	    	],

			[ 'attribute' => 'Nov',
			 'mergeHeader' => true,
			 'label' => '十一月',
	    	],

			[ 'attribute' => 'D', 
			 'mergeHeader' => true,
			 'label' => '十二月',
	    	],
	    ];
	    echo GridView::widget( [
	    	'dataProvider' => $dataProvider,
	    	'filterModel' => $searchModel,
	    	'panel' => [ 'type' => 'info', 'heading' => '电表',
					   'before' => Html::a( '水表', [ '/water', 'type' => 0 ], [ 'class' => 'btn btn-success' ] )],
	    	'toolbar' => [
	    		'centent' => Html::a( '录入', [ 'new', 'type' => 1, 'name' => '电费' ], [ 'class' => 'btn btn-success' ] )
	    	],
	    	'hover' => true,
	    	'columns' => $gridColumn,
	    ] );	
	?>
		
</div>
