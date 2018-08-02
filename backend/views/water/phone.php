<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WaterSearch01 */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="water-meter-index">
     
    <?php	
	
	$gridView = [
		[ 'attribute' => 'build',
		  'value' => 'b.building_name',
		 'filterType' => GridView::FILTER_SELECT2,
			'filter' => $building,
			//'filter' => CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
		  'label' => '楼宇',
		  'width' => '20%',
		],
		['attribute' => 'number',
            'value' => 'r.room_number',
            'label' => '单元',],

		[ 'attribute' => 'name',
		  'value' => 'r.room_name',
		  'width' => '20%',
		],
		
		[ 'attribute' => 'readout',
		  'class' => 'kartik\grid\EditableColumn',
		  'editableOptions' => [
		  'formOptions' => [ 'action' => [ '/water/water' ] ],
		  'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
		  ],
		  'width' => '20%',
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
