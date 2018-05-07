<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use mdm\admin\components\Helper;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">权限指配</h4>',
	/*'options'=>[
        'data-backdrop'=>'static',//点击空白处不关闭弹窗
        'data-keyboard'=>false,
    ],*/
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$view = Url::toRoute( '/admin/role/view' );
$update = Url::toRoute( '/admin/role/update' );
$create = Url::toRoute( '/admin/role/create' );

$updateJs = <<<JS
    $('.view').on('click', function () {
	$('.modal-title').html('路由指配');
        $.get('{$view}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

$updateJs = <<<JS
    $('.update').on('click', function () {
	    $('.modal-title').html('更新');
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
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', '角色');
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<div class="role-index">
   
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '角色列表',
				   'before' => Html::a(Yii::t('rbac-admin', 'New'), '#', [ 'class' => 'btn btn-info create',
													  'data-toggle' => 'modal',
													  'data-target' => '#update-modal',
													  ]) ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br >号'],
            [
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', '名称'),
            ],
            [
                'attribute' => 'ruleName',
                'label' => Yii::t('rbac-admin', '路由规则'),
                'filter' => $rules
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', '描述'),
            ],
            ['class' => 'kartik\grid\ActionColumn',
			 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
			 'buttons' => [
				'update' => function ( $url, $model, $key ) {
		            if($model->name === 'admin'){
		            	return '';
		            }else{
				    	return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', '#', [
				    		'data-toggle' => 'modal',
				    		'data-target' => '#update-modal',
				    		'class' => 'update',
				    		'data-id' => $key,
				    	] );
				    }
	            },
		      'view' => function ( $url, $model, $key ) {
		            if($_SESSION['user']['name'] !== 'admin' & $model->name === 'admin'){
		                        	return '';
		                        }else{
				    	return Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', '#', [
				    		'data-toggle' => 'modal',
				    		'data-target' => '#update-modal',
				    		'class' => 'view',
				    		'data-id' => $key,
				    	] );
				    }
	            },
		      'delete' => function ( $url, $model, $key ) {
		            if($model->name === 'admin'){
		                        	return '';
		                        }else{
				    	return Html::a( '<span class="glyphicon glyphicon-trash"></span>');
				    }
	            },
			],
			'header' => '操<br >作'],
        ],
		'hover' => true,
    ])
    ?>

</div>
