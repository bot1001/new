<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvertisingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertising-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

            /*['attribute'=> 'ad_id',
			'hAlign' => 'center',
			'width' => 'px'],*/
	
           ['attribute'=>  'ad_title',
		   'hAlign' => '',
		   'width' => 'px'],
	
            //'ad_excerpt:ntext',
            ['attribute'=> 'ad_poster',
			'hAlign' => 'center',
			'width' => 'px'],
	
            //'ad_publish_community',
            ['attribute'=> 'ad_type',
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => ['1' => '文章', '2' => '链接'],
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
			 'value' => function($model){
		        $d = ['1' => '文章', '2' => '链接'];
			    return $d[$model->ad_type];
	        },
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'ad_target_value',
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'ad_location',
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => ['1' => '顶部', '2' => '底部'],
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
			 'contentOptions' => function ( $model ) {
		    	return ( $model->ad_location == 1 ) ? [ 'class' => 'bg-info' ] : [];
		    },
			 'value' => function($model){
		        $d = ['1' => '顶部', '2' => '底部'];
			    return $d[$model->ad_location];
	        },
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'ad_created_time',
			 'value' => function($model){
	        	return date('Y-m-d H:i:s', $model->ad_created_time);
	        },
			'hAlign' => 'center',
			'width' => 'px'],
		
		   ['attribute'=> 'ad_end_time',
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'ad_sort',
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'ad_status',
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => ['1' => '正常', '2' => '删除'],
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
			 'contentOptions' => function ( $model ) {
		    	return ( $model->ad_status == 1 ) ? [ ] : [ 'class' => 'bg-warning'];
		    },
			 'value' => function($model){
		        $d = ['1' => '正常', '2' => '删除'];
			    return $d[$model->ad_status];
	        },
			'hAlign' => 'center',
			'width' => 'px'],
	
            ['attribute'=> 'property',
			'hAlign' => 'center',
			'width' => 'px'],
	

            ['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br />作'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridview,
		'panel' => ['type' => 'info', 'heading' => '广告列表',
				   'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-info'])],
		'hover' => true,
    ]); ?>
</div>
