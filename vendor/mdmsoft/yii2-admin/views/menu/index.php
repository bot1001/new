<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
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
$view = Url::toRoute( '/admin/menu/view' );
$update = Url::toRoute( '/admin/menu/update' );
$create = Url::toRoute( '/admin/menu/create' );

$updateJs = <<<JS
    $('.view').on('click', function () {
	$('.modal-title').html('菜单预览');
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
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->title = Yii::t('rbac-admin', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '菜单列表',
				   'before' => Html::a(Yii::t('rbac-admin', '<span class="glyphicon glyphicon-plus"></span>'), ['create'], ['class' => 'btn btn-info'])],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn','header' => '序<br/>号'],
            'name',
            [
                'attribute' => 'menuParent.name',
                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                    'class' => 'form-control', 'id' => null
                ]),
                'label' => Yii::t('rbac-admin', 'Parent'),
            ],
            'route',
            'order',
            ['class' => 'kartik\grid\ActionColumn',
			 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
			 'buttons' => [
				'update' => function ( $url, $model, $key ) {
				    return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', '#', [
				    	'data-toggle' => 'modal',
				    	'data-target' => '#update-modal',
				    	'class' => 'update',
				    	'data-id' => $key,
				    ] );
	            },
		      'view' => function ( $url, $model, $key ) {
				    return Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', '#', [
				    	'data-toggle' => 'modal',
				    	'data-target' => '#update-modal',
				    	'class' => 'view',
				    	'data-id' => $key,
				    ] );
	            },
			],
			 'header' => '操<br />作'],
        ],
		'hover' => true
    ]);
    ?>
<?php Pjax::end(); ?>

</div>
