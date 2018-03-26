<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;//CommunityRealestate

/* @var $this yii\web\View */
/* @var $searchModel app\models\WaterSearch01 */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="water-meter-index">
     
    <?php
	$gridView = [
		[ 'attribute' => 'community',
		 'label' => '小区',
		  'hAlign' => 'center',
		  'value' => 'c.community_name',
		  'filterType' => GridView::FILTER_SELECT2,
			'filter' => CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
		 'width' => '150px'
		],
		[ 'attribute' => 'build',
		  'value' => 'b.building_name',
		  'label' => '楼宇',
		  'width' => '40px',
		  'hAlign' => 'center',
		],
		//['attribute' => 'r.room_number' ],
		[ 'attribute' => 'name',
		  'value' => 'r.room_name',
		  'width' => '50px',
		  'hAlign' => 'center',
		],
		
		[ 'attribute' => 'readout',
		  'class' => 'kartik\grid\EditableColumn',
		  'editableOptions' => [
		  'formOptions' => [ 'action' => [ '/water/water' ] ],
		  'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
		  ],
		  'width' => '40px',
          'hAlign' => 'center',
		],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'layout' => "{items}\n{pager}",
		//'condensed'=>true,
		'responsiveWrap'=>false, //禁止系统适应小屏幕
		'columns' => $gridView,
	] ); ?>
	
</div>
