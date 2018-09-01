<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvertisingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        vertical-align:middle;
        text-align: center;
    }
</style>
<div class="advertising-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

           ['attribute'=>  'ad_title',
               'contentOptions' => ['class' => 'text-left'],
		   'width' => 'px'],
	
            ['attribute'=> 'ad_poster',
                'mergeHeader' => true,
                'format' => 'raw',
                'value' => function($model){
                    return Html::img('http://'.$_SERVER['HTTP_HOST'].$model->ad_poster,['alt' => '缩略图','width' => 80]);
                },
                'enableSorting' => false,
			'width' => '80px'],
	
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
			'width' => 'px'],
	
            ['attribute'=> 'ad_target_value',
			'value' => function($model){
                $date = [1=>'APP',2=>'PC', 3=> '微信'];
                $value = explode(',', $model->ad_target_value); //分裂数组
                $result = '';
                foreach ($value as $v){
                    $result .= $date[$v].' '; //组合数组
                }
                return $result;
            },
             'filterType' => GridView::FILTER_SELECT2,
             'filter' => [1=>'APP',2=>'PC', 3=> '微信'],
             'filterInputOptions' => ['placeholder' => '请选择'],
             'filterWidgetOptions' => [
                     'pluginOptions' => ['allowClear' => true]
             ]
            ],
	
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
			'width' => 'px'],
	
            ['attribute'=> 'ad_created_time'],
		
		   ['attribute'=> 'ad_end_time',
               'class' => 'kartik\grid\EditableColumn',
               'editableOptions' => [
                   'formOptions' => [ 'action' => [ '/advertising/advertising' ] ],
                   'inputType' => \kartik\editable\Editable::INPUT_DATE,
                   'options' => [
                       'pluginOptions'=> [
                           'format' => 'yyyy-mm-dd',
                       ],
                   ],
               ],
           ],
	
            ['attribute'=> 'ad_sort',
			'width' => 'px'],
	
            ['attribute'=> 'ad_status',
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => [0 => '待审核', '1' => '上架', '2' => '下架', 3 => '审核失败'],
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
			 'contentOptions' => function ( $model ) {
		    	return ( $model->ad_status == 1 ) ? [ ] : [ 'class' => 'bg-warning'];
		    },
			 'value' => function($model){
		        $d = [0 => '待审核', '1' => '上架', '2' => '下架', 3 => '审核失败'];
			    return $d[$model->ad_status];
	        },
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions' => [
                    'formOptions' => [ 'action' => [ '/advertising/advertising' ] ],
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'data' => ['1' => '上架', '2' => '下架', 3 => '待审核'],
                ],
                'readonly' => function($model){
                    return (time() > strtotime($model->ad_end_time) || \app\models\Limit::limit($url='/advertising/advertising') == 0 || $model->ad_status == 3); //
                },],
	
            ['attribute'=> 'property',
			 'width' => 'px'],
	

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view}{update}',
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
