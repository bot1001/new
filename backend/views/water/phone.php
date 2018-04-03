<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
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
	
	$c = $_SESSION['user']['community'];
	if(empty($c)){
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->asArray()
			->all();
		$building = CommunityBuilding::find()
			->select('building_name')
			->distinct()
			->asArray()
			->all();
	}else{
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->where(['community_id' => $c])
			->asArray()
			->all();
		$building = CommunityBuilding::find()
			->select('building_name')
			->where(['community_id' => $c])
			->distinct()
			->asArray()
			->all();
	}
	 $comm = ArrayHelper::map($community,'community_id', 'community_name');
	 $build = ArrayHelper::map($building,'building_name', 'building_name');
	
	$gridView = [
		/*[ 'attribute' => 'community',
		 'label' => '小区',
		  'hAlign' => 'center',
		  'value' => 'c.community_name',
		  'filterType' => GridView::FILTER_SELECT2,
			'filter' => $comm,
			//'filter' => CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
		 'width' => '40%'
		],*/
		[ 'attribute' => 'build',
		  'value' => 'b.building_name',
		 'filterType' => GridView::FILTER_SELECT2,
			'filter' => $build,
			//'filter' => CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
		  'label' => '楼宇',
		  'width' => '20%',
		  'hAlign' => 'center',
		],
		//['attribute' => 'r.room_number' ],
		[ 'attribute' => 'name',
		  'value' => 'r.room_name',
		  'width' => '20%',
		  'hAlign' => 'center',
		],
		
		[ 'attribute' => 'readout',
		  'class' => 'kartik\grid\EditableColumn',
		  'editableOptions' => [
		  'formOptions' => [ 'action' => [ '/water/water' ] ],
		  'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
		  ],
		  'width' => '20%',
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
