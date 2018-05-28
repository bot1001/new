<?php

use yii\ helpers\ Html;
use kartik\ grid\ GridView;
use app\models\CostName;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class = "modal-tittle">费项管理</h4>',
    ] );
$u_Url = Url::toRoute( 'update' );
$n_Url = Url::toRoute('create');

$uJs = <<<JS
   $('.update').on('click',function(){
      $.get('{$u_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs( $uJs );

$nJs = <<<JS
   $('.new').on('click',function(){
      $.get('{$n_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs ($nJs);

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\CostNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '费项管理';
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="cost-name-index">

    <?php
	
	    $c = Yii::$app->getSession()->getflash('m');
	if($c == 1){
		echo "<script>alert('抱歉，您的权限不足！')</script>";
	}
	?>
	<?php
	$gridview = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'
		],

		//'cost_id',
		['attribute' => 'cost_name',
		'filterType' => GridView::FILTER_SELECT2,
			'filter' => CostName::find()->select( [ 'cost_name' ] )->where( [ 'level' => '0' ] )->orderBy( 'cost_name' )->indexBy( 'cost_name' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			]
		],
		
		['attribute' => 'level',
		 'value' => function($model){
	     	$a = ['父级','子级'];
	     	return $a[$model['level']];
	     },
		 'filterType' => GridView::FILTER_SELECT2,
		 'filter' => ['父级','子级'],
		 'filterInputOptions' => [ 'placeholder' => '请选择' ],
		 'filterWidgetOptions' => [
		 	'pluginOptions' => [ 'allowClear' => true ],
		 ],
		 'contentOptions' => function ( $model ) {
		 	return ( $model->level == 0 ) ? [ 'class' => 'bg-info' ] : [];
		 },
		 'hAlign' => 'center' ],
		
		/*[ 'attribute' => 'parent',
			'value' => function ( $model ) {
		        $cost_name = CostName::find()->select('cost_name')->where(['cost_id' => $model->parent])->asArray()->one();
				return $cost_name['cost_name'];
			},
		  'filterType' => GridView::FILTER_SELECT2,
			'filter' => CostName::find()->select( [ 'cost_name', 'cost_id' ] )->where( [ 'parent' => '0' ] )->orderBy( 'cost_name' )->indexBy( 'cost_id' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'hAlign' => 'center',
		],*/

		[ 'attribute' => 'inv',
			'value' => function ( $model ) {
				return $model->inv == 0 ? '否' : '是';//( $model == 1 ) ? [ 'class' => 'bg-info' ] : []; // 根据值改变底色
			},
			'filterType' => Gridview::FILTER_SELECT2,
			'filter' => [ '否', '是' ],
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ], //'pluginOptions'=>['allowClear'=>true],
			],
		    'contentOptions' => function ( $model ) {
				return ( $model->inv == 0 ) ? [ 'class' => 'bg-warning' ] : [];
			},
			'hAlign' => 'center'
			],
		
		[ 'attribute' => 'price',
		'hAlign' => 'center'],
		[ 'attribute' => 'create_time',
		 'width' => '180px',
		'hAlign' => 'center'],
		[ 'attribute' => 'update_time',
		 'width' => '180px',
		'hAlign' => 'center'],
		[ 'attribute' => 'builder',
		 'value' => 'sys.name',
		'hAlign' => 'center'],
		[ 'attribute' => 'property',
		'hAlign' => 'center'],

		[ 'class' => 'kartik\grid\ActionColumn',
			'template' => '{update}',
			'buttons' => [
				'update' => function ( $url, $model, $key ) {
					return Html::a( '编辑', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#update-modal',
						'class' => 'update',
						'data-id' => $key,
					] );
				}
			],
			'header' => '操作'
		],
	];

	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [ 'type' => 'info', 'heading' => '费项列表',
			'before' => Html::a( 'New', '#', [ 'class' => 'btn btn-info new',
													  'data-toggle' => 'modal',
													  'data-target' => '#update-modal',
													  ] )
		],
		'columns' => $gridview,
		'hover' => true,
	] );
	?>
</div>