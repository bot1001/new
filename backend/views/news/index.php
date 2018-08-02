<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">公告栏相关操作</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$v_Url = Url::toRoute( [ 'view', 'note' => '1' ] );
$u_Url = Url::toRoute( [ 'update' ] );
$c_Url = Url::toRoute( [ 'create' ] );

$cJs = <<<JS
    $('.v').on('click', function () {
        $.get('{$v_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

$cJs = <<<JS
    $('.c').on('click', function () {
        $.get('{$c_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

$cJs = <<<JS
	
	$('.u').on('click', function () {
        $.get('{$u_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

Modal::end();

$this->title = '公告栏';
//$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="community-news-index">

    <?php

	$gridview =[
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

            ['attribute'=> 'community_id',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $comm,
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
                'contentOptions' => ['class' => 'text-left'],
			'width' => '150px'],
		
            ['attribute'=> 'title',
                'contentOptions' => ['class' => 'text-left'],
                ],
		
            ['attribute'=> 'excerpt',
                'contentOptions' => ['class' => 'text-left'],],
		
            ['attribute'=> 'content',
			 'format' => 'raw',
			 'value' => function($model){
	        	$url = Yii::$app->urlManager->createUrl(['news/view','id'=>$model->news_id]);
	        	return Html::a( '查看', '#', [
	        		'data-toggle' => 'modal',
	        		'data-target' => '#view-modal',
	        		'class' => 'v',
	        	] );
	        },],
		
            ['attribute'=> 'post_time',
			 'value' => function($model){
	           	return date('Y-m-d H:i:s', $model->post_time);
	           },
			'width' => '150px'],
		
            ['attribute'=> 'update_time',
	         'value' => function($model){
	           	return date('Y-m-d H:i:s', $model->update_time);
	           },
			'width' => '150px'],
		
            /*['attribute'=> 'view_total',
			'width' => 'px'],*/
		
            ['attribute'=> 'stick_top',
			 'value' => function($model){
	         	$top = ['不置顶', '置顶'];
	         	return $top[$model['stick_top']];
	         },
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/news/news' ] ], // point to the new action        
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
				'data' => ['不置顶', '置顶'],
			],
			'width' => '30px'],
		
            ['attribute'=> 'status',
			 'value' => function($model){
	        	$s = [ '1'=> '正常', '2' => '预发布', '3' => '过期'];
	        	return $s[$model['status']];
	        },
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/news/news' ] ], // point to the new action        
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
				'data' => [ '1'=> '正常', '2' => '预发布', '3' => '过期'],
			],
			'width' => '20px'],

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => '{update}{view}',
			 'header' => '操<br />作'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '公告列表',
				   'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#view-modal',
				'class' => 'btn btn-info c',
			] ),],
        'columns' => $gridview,
    ]); ?>
</div>
