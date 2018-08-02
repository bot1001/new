<?php

use yii\ helpers\ Html;
use kartik\ grid\ GridView;
use app\ models\ SysRole;
use app\ models\ CommunityBasic;
use app\ models\ CommunityRealestate;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SysuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '后台用户列表';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="sys-user-index">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php
	$gridColumn = [

		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序 <br />号'
		],

		//'id',
		/*[ 'attribute' => 'real_name',
			'width' => '150px'
		],*/

		[ 'attribute' => 'company',
            'contentOptions' => ['class' => 'text-left'],
		  'width' => '100px'
		],

		[ 'attribute' => 'community',
			'value' => function ( $model ) {
				$community = CommunityBasic::find()->select( [ 'community_name' ] )->where( [ 'community_id' => $model->community ] )->one();
				if ( empty( $model->community ) ) {
					return '';
				} else {
					return $community[ 'community_name' ];
				}

			},
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => CommunityBasic::find()->select( [ 'community_name', 'community_id' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),

			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( $model->name == 'admin' ); // 判断活动列是否可编辑
			},
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => CommunityBasic::find()->select( [ 'community_name', 'community_id' ] )->orderBy( 'community_name' )->indexBy( 'community_id' )->column(),
			],
            'contentOptions' => ['class' => 'text-left'],
			'width' => '200px'
		],

		[ 'attribute' => 'name',
            'contentOptions' => ['class' => 'text-left'],
			'width' => '200px'
		],

		[ 'attribute' => 'status',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => [ 1 => '正常', 0 => '禁用', 2 => '锁定' ],
			'value' => function ( $model ) {
				$date = [ 1 => '正常', 0 => '禁用', 2 => '锁定' ];
				return $date[ $model[ 'status' ] ];
			},
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( $model->role == 1 ); // 判断活动列是否可编辑
			},
			'class' => 'kartik\grid\EditableColumn',

			'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => [ 1 => '正常', 0 => '禁用', 2 => '锁定' ],
			],
			'width' => '60px'
		],

		[ 'attribute' => 'role',
			'value' => function ( $model ) {
				$date = SysRole::find()->select( [ 'name' ] )->where( [ 'id' => $model->role ] )->asArray()->one();
				return $date[ 'name' ];
			},
			'readonly' => function ( $model ) {
				return ( $model->name == 'admin' );
			},
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => SysRole::find()->select( [ 'name', 'id' ] )->orderBy( 'name' )->indexBy( 'id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => SysRole::find()->select( [ 'name', 'id' ] )->where( [ 'or not like', 'name', [ 'admin' ] ] )->orderBy( 'name' )->indexBy( 'id' )->column(),
			],
			'width' => '120px'
		],



		/*['attribute' => 'create_time',
			'width' => 'px'],
		
		['attribute' => 'update_time',
			'width' => 'px'],
		
		['attribute' => 'update_id',
			'width' => 'px'],*/

		/*['attribute' => 'new_pd',
			'width' => 'px'],*/

		[ 'attribute' => 'phone',
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'width' => 'px'
		],

		[ 'attribute' => 'comment',
			'value' => function ( $model ) {
				if ( empty( $model->comment ) ) {
					return '';
				} else {
					return $model->comment;
				}
			},
            'contentOptions' => ['class' => 'text-left'],
			'width' => '200px'
		],

		[ 'class' => 'kartik\grid\ActionColumn',
			'template' => '{update}{view}',
			'width' => '60px',
			'header' => '操<br />作'
		],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [ 'type' => 'info', 'heading' => '后台用户列表',
			'before' => Html::a( 'New', [ 'create' ], [ 'class' => 'btn btn-success' ] )
		],
		'hover' => true,
		'columns' => $gridColumn,
	] );
	?>
</div>