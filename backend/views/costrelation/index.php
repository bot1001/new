<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">费项批量关联</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$c_Url = Url::toRoute( 'create' );
$u_Url = Url::toRoute( 'update' );
$cJs = <<<JS
    $('.create').on('click', function () {
        $.get('{$c_Url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $cJs );

$uJs = <<<JS
    $('.update').on('click', function () {
        $('.modal-title').html('更新');
        $.get('{$u_Url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $uJs );

Modal::end();


/* @var $this yii\web\View */
/* @var $searchModel app\models\CostRelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '费项列表';
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="cost-relation-index">

	<?php
	
	$message = Yii::$app->getSession()->getFlash('success'); //获取提示信息
				
	$gridColumn = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'
		],

		[ 'attribute' => 'community',
			'value' => 'c.community_name',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => $comm,
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'contentOptions' => ['class' => 'text-left'],
			'width' => '150px'
		],

		[ 'attribute' => 'building_id',
			'value' => 'b.building_name',
			'width' => '50px'
		],

		[ 'attribute' => 'number',
			'value' => 'r.room_number',
		 //'mergeHeader' => true,
			'width' => '80px'
		],

		[ 'attribute' => 'room_name',
		  'label' => '房号',
		  'value' => 'r.room_name',
		  'width' => 'px'
		],

		[ 'attribute' => 'name',
            'value' => 'cos.cost_name',
            'label' => '名称',
            'contentOptions' => ['class' => 'text-left'],
            'width' => '100px'
		],

		[ 'attribute' => 'price',
		 'value' => 'cos.price',
			//'mergeHeader' => true,
			'width' => 'px'
		],

		[ 'attribute' => 'from',
			'width' => 'px'
		],
		
		['attribute' => 'status',
		 'value' => function($model){
	    	return $model->status== 0 ? '禁用' : '启用';
	    },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['0' => '禁用', '1' => '启用'],
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=> true]
            ],
		 //单元格背景色变换
		 'contentOptions' => function ( $model ) {
		 	return ( $model->status == 0 ) ? [ 'class' => 'bg-orange' ] : [];
		 },
		 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/costrelation/relation' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
				'data' => ['禁用', '启用'],
		],
		],

		[ 'attribute' => 'property', ],

		[ 'class' => 'kartik\grid\ActionColumn',
			'template' => '{update}',
				'buttons' => [
					'update' => function ( $url, $model, $key ) {
						return Html::a( '<span class = "glyphicon glyphicon-pencil"></span>', '#', [
							'data-toggle' => 'modal',
							'data-target' => '#update-modal', //modal 名字
							'class' => 'update', //操作名
							'data-id' => $key,
						] );
					},
			],
			'header' => '操<br />作'
		],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [ 'type' => 'info', 'heading' => '费项关联',
			'before' => Html::a( '<span class = "glyphicon glyphicon-plus"></span>', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#update-modal',
				'class' => 'btn btn-info create',
			] ),
		],
		'hover' => true,
		'columns' => $gridColumn,
	] );
	?>
</div>