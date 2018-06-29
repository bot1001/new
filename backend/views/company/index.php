<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">公司操作</h4>',
    ] );

$n_Url = Url::toRoute( '/company/create');

$nJs = <<<JS
   $('.new').on('click',function(){
      $('.modal-title').html('新建');
      $.get('{$n_Url}',{id:$(this).closest('tr').data('key')},
         function (data) {
            $('.modal-body').html(data);
      });
   });
JS;
$this->registerJs ($nJs);

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公司';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <?php 
	
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序号'],
            'name',
            ['attribute'=> 'creator',
			'value' => 'cr.name'],
            ['attribute'=> 'create_time',
			'value' => function($model){
	        	return date('Y-m-d H:i:s', $model->create_time);
	        },
			'hAlign' => 'center'],
            'property',
		/*['class' => 'kartik\grid\ActionColumn',
		 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
		 'header' => '操作'],*/
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '公司',
				   'before' => Html::a( '<span class="glyphicon glyphicon-plus"></span>', '#', [
			        	'data-toggle' => 'modal',
			        	'data-target' => '#update-modal', 
		                'class' => 'btn btn-success new',
			        ] )],
        'columns' => $gridview,
    ]); ?>
</div>
