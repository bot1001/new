<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">权限指配</h4>',
    ] );
$u_Url = Url::toRoute( '/account/update' );
$n_Url = Url::toRoute(['/account/create', 'a' => $k]);

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

$uJs = <<<JS
    $('.new').on('click', function () {
	    $('.modal-title').html('创建');
        $.get('{$n_Url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs ($uJs);

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>

<div class="user-account-index"style="max-width: 1000px">
   
    <?php
	$gridColumn = [
		['class' => 'kartik\grid\SerialColumn',
		'header' => '序<br />号'],
		
		['attribute' => 'number',
		'value' => 'work.work_number',
            'contentOptions' => ['class' => 'text-left'],
		'label' => '工号'],
		
		['attribute' => 'user_name',
		'class' => 'kartik\grid\EditableColumn',
		 'editableOptions' => [
		 	'formOptions' => [ 'action' => [ '/account/account' ] ],
		 	'inputType' => \kartik\editable\Editable::INPUT_TEXT,
		 ],],

        ['attribute' => 'gender',
            'value' => function($model){
                $date = [1 => '男', 2 => '女'];
                return $date[$model->gender];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['1' => '男', '2' => '女'],
            'filterInputOptions' => ['placeholder' => '请选择'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            ],
		
		['attribute' => 'mobile_phone',
		 'format' => 'raw',
		 'value' => function($model){
	     	$url = Yii::$app->urlManager->createUrl(['/workr/create', 'user_id' => $model->user_id]);
	     	return Html::a($model->mobile_phone, $url);
	     },
        ],
		
		['attribute' => 'status',
		 'filterType' => GridView::FILTER_SELECT2,
		 'filter' => [1 => '正常', 2 => '删除', 3 => '锁定'],
		 'filterInputOptions' => ['placeholder' => '请选择……'],
		 'filterWidgetOptions' => [
	     	'pluginOptions' => ['allowClear' => true],
	     ],
		 'value' => function($model){
		    $d = [1 => '正常', 2 => '删除', 3 => '锁定'];
	     	return $d[$model->status];
	     },
		 'class' => 'kartik\grid\EditableColumn',
		 'editableOptions' => [
		 	'formOptions' => [ 'action' => [ '/account/account' ] ],
		 	'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
		        'data' => [1 => '正常', 2 => '删除', 3 => '锁定'],
		 ],
		],
		
		['attribute' => 'more',
		 'mergeHeader' => true,
		'format' => 'raw',
		'value' => function($model){
	    	$url = Yii::$app->urlManager->createUrl( [ '/workr/index', 'id' => $model->account_id] );
	    	return Html::a('more', $url, ['title' => '查看关联小区']);
	    },
        ],
		
		['class' => 'kartik\grid\ActionColumn',
		 'template' => '{view}{update}',
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
		'panel' => ['type' => 'info', 'heading' => '内部员工',
				   'before' => Html::a( '<span class="glyphicon glyphicon-plus"></span>', '#', [
				'data-toggle' => 'modal',
				'data-target' => '#update-modal', 
		        'class' => 'btn btn-success new',
			] )],
        'columns' => $gridColumn,
    ]); ?>
</div>
