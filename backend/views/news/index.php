<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\CommunityBasic;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">公告栏相关操作</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$v_Url = Url::toRoute( [ 'view' ] );
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
<div class="community-news-index">

    <?php
	$c = $_SESSION['user']['community'];
	if(empty($c)){
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->asArray()
			->all();
	}else{
		$community = CommunityBasic::find()
			->select('community_id, community_name')
			->where(['community_id' => $c])
			->asArray()
			->all();
	}
	 $comm = ArrayHelper::map($community,'community_id', 'community_name');
	
	$gridview =[
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],

            ['attribute'=> 'community_id',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
			 //'filter' => CommunityBasic::find()->select(['community_name'])->orderBy('community_name')->indexBy('community_id')->column() ,
			 'filter' => $comm,//CommunityBasic::find()->select( [ 'community_name' ] )->orderBy( 'community_name' )->indexBy( 'community_name' )->column(),
			 'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			//'hAlign' => 'center',
			'width' => '150px'],
		
            ['attribute'=> 'title',
			 'value' => 'B',
			'hAlign' => 'left',
			'width' => 'px'],
		
            ['attribute'=> 'excerpt',
			'hAlign' => 'center',
			'value' => 'E', 
			'width' => 'px'],
		
            ['attribute'=> 'content',
			 'format' => 'raw',
			 'value' => function($model){
	        	$url = Yii::$app->urlManager->createUrl(['news/view','id'=>$model->news_id]);
	        	return Html::a( '查看', '#', [
	        		'data-toggle' => 'modal',
	        		'data-target' => '#view-modal',
	        		'class' => 'v',
	        	] );
	        },
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'post_time',
			 'value' => function($model){
	           	return date('Y-m-d H:i:s', $model->post_time);
	           },
			'hAlign' => 'center',
			'width' => '150px'],
		
            ['attribute'=> 'update_time',
	         'value' => function($model){
	           	return date('Y-m-d H:i:s', $model->update_time);
	           },
			'hAlign' => 'center',
			'width' => '150px'],
		
            /*['attribute'=> 'view_total',
			'hAlign' => 'center',
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
			'hAlign' => 'center',
			'width' => '30px'],
		
            ['attribute'=> 'status',
			 'value' => function($model){
	        	$s = [ '1'=> '正常', '3' => '过期'];
	        	return $s[$model['status']];
	        },
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/news/news' ] ], // point to the new action        
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
				'data' => [ '1'=> '正常', '3' => '过期'],
			],
			'hAlign' => 'center',
			'width' => '20px'],

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => '{update}',
			 'header' => '操<br />作'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '公告列表',
				   'before' => Html::a('New', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#view-modal',
				'class' => 'btn btn-info c',
			] ),],
        'columns' => $gridview,
    ]); ?>
</div>
