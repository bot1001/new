<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\CommunityBasic;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InformationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息';
?>
<div class="information-index">

    <?php 
	
	$c = $_SESSION['community'];
	
	$comm = CommunityBasic::find()
		->select('community_name, community_id')
		->where(['in', 'community_id', $c])
		->orderBy('community_name DESC')
		->indexBy('community_id')
		->column();
	
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

            //'remind_id',
            ['attribute' => 'community',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $comm,
			 'filterInputOptions' => ['placeholder' => '请选择…'],
			 'filterWidgetOptions' => [
		         'pluginOptions' =>  ['allowClear' => true],
	         ],
			'width' => '200px'],
		
            ['attribute' => 'detail',
			'hAlign' => ''],
		
            ['attribute' => 'times',
			 'value' => function($model){
		         return $model->times.'次';
	         },
			'hAlign' => 'center'],
		
            ['attribute' => 'reading',
			 'value' => function($model){
	        	$read = ['否', '是'];
	        	return $read[$model->reading];
	        },
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => ['否', '是'],
			 'filterInputOptions' => ['placeholder' => ''],
			 'filterWidgetOptions' => [
	         	'pluginOptions' => ['allowClear' => true],
	         ],
			'hAlign' => 'center'],
		
            ['attribute' => 'target',
			'hAlign' => ''],
		
            ['attribute' => 'ticket_number',
			 'format' => 'raw',
			 'value' => function($model){
	         	$url = Yii::$app->urlManager->createUrl( [ '/ticket/index', 'name' => '待接单', 'c' => $model->community ] );
	         	return Html::a($model->ticket_number, $url);
	         },
			'hAlign' => 'center'],
		
            ['attribute' => 'remind_time',
			 'value' => function($model){
	         	return date('Y-m-d H:i:s', $model->remind_time);
	         },
			 'mergeHeader' => true,
			'hAlign' => 'center'],
		
            ['attribute' => 'property',
			'hAlign' => ''],
		

            /*['class' => 'kartik\grid\ActionColumn',
			'header' => '操作'],*/
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '消息列表'],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
