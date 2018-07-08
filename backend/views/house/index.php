<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use mdm\admin\components\Helper;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">添加业主信息</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$add_Url = Url::toRoute( [ '/house/create' ] );
$up_Url = Url::toRoute( [ '/house/update' ] );

$cJs = <<<JS
    $('.add').on('click', function () {
        $.get('{$add_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

$cJs = <<<JS
    $('.up').on('click', function () {
        $.get('{$up_Url}', { id: $(this).closest('tr').data('key') },
           function(data){
              $('.modal-body').html(data);
           }
        );
    });
JS;
$this->registerJs( $cJs );

Modal::end();

/* @var $this yii\web\View */
/* @var $searchModel app\models\houseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房屋信息';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-index">

    <?php
    $message=Yii::$app->getSession()->getFlash('fail');
    if($message == 1){
        echo '<script>alert("更新对象不能为空！")</script>';
    }elseif ($message == 2){
        echo '<script>alert("添加对象不能为空！")</script>';
    }
    ?>

    <?php
	$gridview = [
		
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],
				
            ['attribute'=> 'community',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $community,
			 'filterInputOptions' => ['placeholder' => '请选择'],
			 'filterWidgetOptions' => [
		         'pluginOptions' => ['allowClear' => true],
	         ],
			 'label' => '小区',
			'hAlign' => 'center',
			'width' => 'px'],
		
		    ['attribute'=> 'building',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => $building,
			 'filterInputOptions' => ['placeholder' => '请选择'],
			 'filterWidgetOptions' => [
		         'pluginOptions' => ['allowClear' => true],
	         ],
			 'label' => '楼宇',
			'hAlign' => 'center',
			'width' => 'px'],
		
		    ['attribute'=> 'number',
			 'value' => function($model){
	        	return $model->number.'单元';
	        },
			 'label' => '单元',
			'hAlign' => 'center',
			'width' => 'px'],
		
		   ['attribute'=> 'room_name',
			 'label' => '房号',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'name',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'phone',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => '150px'],
		
            ['attribute'=> 'IDcard',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => '200px'],
		
            ['attribute'=> 'status',
			 'value' => function($model){
	         	$date = ['0' => '停用', '1' => '在用'];
	         	return $date[$model->status];
	         },
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => ['0' => '停用', '1' => '在用'],
			 'filterInputOptions' => ['placeholder' => '请选择'],
			 'filterWidgetOptions' => [
		         'pluginOptions' => ['allowClear' => true],
	         ],
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
		        'data' => ['停用', '在用'],
			],
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'address',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_TEXT,
			],
			'width' => 'px'],

           ['attribute'=> 'politics',
               'value' => function($model){
	               $date = ['0' => '否', '1' => '是'];
	               return $date[$model->politics];
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => ['0' => '否', '1' => '是'],
               'filterInputOptions' => ['placeholder' => '请选择'],
               'filterWidgetOptions' => [
                   'pluginOptions' => ['allowClear' => true],
               ],
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                 'data' => ['0' => '否', '1' => '是'],
			],
               'contentOptions' => function($model){
	               return ($model->politics == 0) ? ['class' => 'bg-success' ]:[];
               },
               'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'property',
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'formOptions' => [ 'action' => [ '/house/house' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['class' => 'kartik\grid\ActionColumn',
			 'template' => Helper::filterActionColumn('{delete}'),
			'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '业主资料',
				   'before' => Html::a('<span class = "glyphicon glyphicon-plus"></span>', '#', [ 
		                'data-toggle' => 'modal',
						'data-target' => '#view-modal',
						'class' => 'btn btn-info add' ] )],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
