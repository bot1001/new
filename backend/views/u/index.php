<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use app\models\CommunityBasic;
use app\models\SysRole;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">权限指配</h4>',
	/*'options'=>[
        'data-backdrop'=>'static',//点击空白处不关闭弹窗
        'data-keyboard'=>false,
    ],*/
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$assignment = Url::toRoute( '/assignment/view' );
$update = Url::toRoute( '/u/update' );
$create = Url::toRoute( '/sysuser/create' );

$updateJs = <<<JS
    $('.order').on('click', function () {
	$('.modal-title').html('权限指配');
        $.get('{$assignment}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

$updateJs = <<<JS
    $('.update').on('click', function () {
	    $('.modal-title').html('编辑');
        $.get('{$update}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

$updateJs = <<<JS
    $('.create').on('click', function () {
	    $('.modal-title').html('创建');
        $.get('{$create}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t( 'rbac-admin', '后台账户' );
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="user-index">

	<?php

	$gridview = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序 <br />号'
		],
		
		[ 'attribute' => 'company',
		  'value' => 'com.name',
		  'filterType' => GridView::FILTER_SELECT2,
		  'filter' => $company,
		  'filterInputOptions' => [ 'placeholder' => '请选择' ],
		  'filterWidgetOptions' => [
		  	'pluginOptions' => [ 'allowClear' => true ],
		  ],
		  /*'readonly' => function ( $model, $key, $index, $widget ) {
		  	return ( $model->role == 1 ); // 判断活动列是否可编辑
		  },*/
		  'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '关联小区',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => $company,
			],
		  'width' => '100px'
		],

		[ 'attribute' => 'community',
		 'format' => 'raw',
		 'value' => function($models){
	          $url = Yii::$app->urlManager->createUrl(['#']);
	      	  return Html::a('点击设置', $url);
	      },
		 'hAlign' => 'center',
		 'width' => '200px'
		],

		[ 'attribute' => 'name',
		  'format' => 'raw',
			'value' => function ( $model ) {
				return Html::a( $model->name, '#', [
					'data-toggle' => 'modal',
					'data-target' => '#update-modal',
					'class' => 'order',
				] );
			},
			'width' => '200px'
		],

		[ 'attribute' => 'status',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => [ 1 => '正常', 0 => '禁用', 2 => '其他' ],
		    'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'value' => function ( $model ) {
				$date = [ 1 => '正常', 0 => '禁用', 2 => '其他' ];
				return $date[ $model[ 'status' ] ];
			},
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( $model->role == 1 ); // 判断活动列是否可编辑
			},
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '状态',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => [ 1 => '正常', 0 => '禁用', 2 => '其他' ],
			],
			'hAlign' => 'center',
			'width' => '60px'
		],

		[ 'attribute' => 'role',
			'value' => 'ro.name',
			'readonly' => function ( $model ) {
				return ( $model->name == 'admin' );
			},
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => $date,
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '角色',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => $date,
			],
		 'label' => '数据角色',
			'hAlign' => 'center',
			'width' => '120px'
		],
		
		[ 'attribute' => 'phone',
			'class' => 'kartik\grid\EditableColumn',
			'editableOptions' => [
				'header' => '手机号码',
				'formOptions' => [ 'action' => [ '/sysuser/sysuser' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => 'px'
		],

		[ 'attribute' => 'comment',
			'width' => '200px'
		],

		[
			'class' => 'kartik\grid\ActionColumn',
			'template' => Helper::filterActionColumn( [ 'update', 'delete' ] ),
		    'buttons' => [
				'update' => function ( $url, $model, $key ) {
					return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#update-modal',
						'class' => 'update',
						'data-id' => $key,
					] );
				},
			],
		],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [ 'type' => 'info', 'heading' => '账户列表',
				   'before' => Html::a( 'New', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#update-modal', 
		        'class' => 'btn btn-success create',
			] )],
		'columns' => $gridview,
		'hover' => true,
	] );
	?>
</div>