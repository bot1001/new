<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;//CommunityRealestate

/* @var $this yii\web\View */
/* @var $searchModel app\models\WaterSearch01 */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '费表录入';
$this->params['breadcrumbs'][] = ['label' => '读数一览表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-meter-index">

   <?php // $this->render('_search', ['model' => $searchModel]); ?>
   
    <?php
	    $s = Yii::$app->getSession()->getflash('s');
	
	    $c = Yii::$app->getSession()->getflash('m');
	    if($c == 1){
	    	echo "<script>alert('抱歉，您无此权限！')</script>";
	    }elseif($c == 2){
			echo "<script>alert('请勿重复创建！')</script>";
		}elseif($c == 3){
			echo "<script>alert('水费已生成，请勿重复生成')</script>";
		}elseif($c == 4){
			echo "<script>alert('当月水表读数未更新，请先更新后生成水费！')</script>";
		}
	
	    if(isset($s))
		{
			echo "<script>alert('$s')</script>";
		}
	?>
   
    <?php
	
	$gridView = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'
		],

		//'id',
		//'realestate_id',

		[ 'attribute' => 'community',
		 'label' => '小区',
		  'hAlign' => 'center',
		  'value' => 'c.community_name',
		  'filterType' => GridView::FILTER_SELECT2,
		  'filter' => CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
		  'filterInputOptions' => [ 'placeholder' => '请选择' ],
		  'filterWidgetOptions' => [
		  	'pluginOptions' => [ 'allowClear' => true ],
		  ]
		],
		[ 'attribute' => 'build',
		  'value' => 'b.building_name',
		  'label' => '楼宇',
		  'width' => '80px',
		  'hAlign' => 'center',
		],
		//['attribute' => 'r.room_number' ],
		[ 'attribute' => 'name',
		  'value' => 'r.room_name',
		  'width' => '100px',
		  'hAlign' => 'center',
		],
		[ 'attribute' => 'year',
			'value' => function ( $model ) {
				return $model->year . '年';
			},
			'width' => 'px',
			'hAlign' => 'center',
		], 
		[ 'attribute' => 'month',
			'value' => function ( $model ) {
				return $model->month . '月';
			},
			'width' => 'px',
			'hAlign' => 'center',
		],
		[ 'attribute' => 'readout',
		  'class' => 'kartik\grid\EditableColumn',
		  'editableOptions' => [
		      'formOptions' => [ 'action' => [ '/water/water' ] ],
		      'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
		  ],
		  'hAlign' => 'right'
		],
		
		[ 'attribute' => 'property',
		  'mergeHeader' => true,
		  'format' => ['date','php:Y-m-d H:i:s'],//'format' =>['date','php:Y-m-d H:i:s'],
		  'hAlign' => 'center',
		 'width' => '180px'
		],
		[ 'class' => 'kartik\grid\CheckboxColumn'],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [ 'type' => 'info', 'heading' => '录入水表读数',
				   'before' => Html::a( '更新', [ 'create', 'type' => 0, 'name' => '水费' ], [ 'class' => 'btn btn-info' ] )],
		'toolbar' => [
	    		'centent' => Html::a( '提交', [ 'fee', 'type' => 0, 'name' => '水费' ], [ 'class' => 'btn btn-success' ] ),
		'{toggleData}'
	    	],
		//'toolbar' => [],
		'columns' => $gridView,
		'hover' => true
	] ); ?>
</div>
