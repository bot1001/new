<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\CommunityBasic;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class = "modal-tittle">楼宇管理</h4>',
    ] );
$u_Url = Url::toRoute( '/building/update' );
$n_Url = Url::toRoute('/building/create');

$uJs = <<<JS
   $('.update').on('click',function(){
      $('.modal-title').html('更新');
      $.get('{$u_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs( $uJs );

$nJs = <<<JS
   $('.new').on('click',function(){
      $('.modal-title').html('创建');
      $.get('{$n_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs ($nJs);

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '楼宇列表';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-building-index">

    <?php
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],
		
		    ['attribute' => 'company',
			 'value' => 'com.name',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/building/building' ] ],
		        'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => $company,
			],
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => $company,
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
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
			 'template' => '{update}{view}',
			 'buttons' => [
				'update' => function ( $url, $model, $key ) {
					return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#update-modal',
						'class' => 'update',
						'data-id' => $key,
					] );
				}
			],
			'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '楼宇列表',
				   'before' => Html::a( '<span class="glyphicon glyphicon-plus"></span>', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#update-modal', 
		        'class' => 'btn btn-success new',
			] )],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
