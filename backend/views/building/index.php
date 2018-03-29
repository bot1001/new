<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\CommunityBasic;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '楼宇列表';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-building-index">

    <?php
	$gridview = [
            /*['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],*/
		
		    ['attribute' => 'building_id',
			'hAlign' => 'center',
			'width' => 'px',],
		
            ['attribute' => 'community_id',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
			'filter' => CommunityBasic::find()->select( [ 'community_name', 'community_id' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			], 
			 'label' => '小区',
			'hAlign' => 'center',
			'width' => 'px',],
		
		   ['attribute' => 'building_name',
			'hAlign' => 'center',
			'width' => 'px',],
		
            ['attribute' => 'creater',
			 'mergeHeader' => true,
			 'value' => 'creater0.name',
			'hAlign' => 'center',
			'width' => 'px',],
		
		    ['attribute' => 'create_time',
			 'value' => function($model)
			 {
	         	return date('Y-m-d H:i:s', $model->create_time);
	         },
			'hAlign' => 'center',
			'width' => 'px',],
		
		    
		
            /*['attribute' => 'building_parent',
			'hAlign' => 'center',
			'width' => 'px',],*/
		

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => '{update}',
			'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '房屋列表',
				   'before' => Html::a('New', ['create'], ['class' => 'btn btn-success'])
				   ],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
