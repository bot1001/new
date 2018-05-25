<?php

use yii\ helpers\ Html;
use kartik\ grid\ GridView;
use yii\ bootstrap\ Modal;
use yii\ helpers\ Url;
use app\ models\ CommunityBasic;
use app\ models\ CommunityBuilding;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">费项关联</h4>',
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
<div class="cost-relation-index">

	<?php
			
	$gridColumn = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'
		],

		//'id',
		[ 'attribute' => 'community',
			'value' => 'c.community_name',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => $comm,
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'hAlign' => 'center',
			'width' => '150px'
		],

		[ 'attribute' => 'building',
			'value' => 'b.building_name',
			'hAlign' => 'center',
			'width' => '50px'
		],

		[ 'attribute' => 'number',
			'value' => 'r.room_number',
		 //'mergeHeader' => true,
			'hAlign' => 'center',
			'width' => '80px'
		],

		[ 'attribute' => 'room_name',
		  'label' => '房号',
		  'value' => 'r.room_name',
		  'hAlign' => 'center',
		  'width' => 'px'
		],

		[ 'attribute' => 'name',
			'value' => 'cos.cost_name',
		 'label' => '名称',
			'hAlign' => 'center',
			'width' => '100px'
		],

		[ 'attribute' => 'price',
		 'value' => 'cos.price',
			//'mergeHeader' => true,
			'hAlign' => 'center',
			'width' => 'px'
		],

		[ 'attribute' => 'from',
			'hAlign' => 'center',
			'width' => 'px'
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
			'before' => Html::a( 'New', '#', [
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