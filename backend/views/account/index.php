<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-index">
   
    <?php
	$gridColumn = [
		['class' => 'kartik\grid\SerialColumn',
		'header' => '序<br />号'],
		
		['attribute' => 'number',
		'value' => 'work.work_number',
		'label' => '工号'],
		
		'user_name',
		
		['attribute' => 'mobile_phone',
		'hAlign' => 'center'], 
		
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
		 'hAlign' => 'center'
		],
		
		['attribute' => 'more',
		 'mergeHeader' => true,
		'format' => 'raw',
		'value' => function($model){
	    	$url = Yii::$app->urlManager->createUrl( [ '#'] );
	    	return Html::a('more', $url);
	    },
		'hAlign' => 'center'],
		
		['class' => 'kartik\grid\ActionColumn',
		 'template' => '{view}{update}',
		'header' => '操<br />作'],
	];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'primary', 'heading' => '内部员工',
				   'before' => Html::a('New', ['create','a' => $k], ['class' => 'btn btn-info'])],
        'columns' => $gridColumn,
    ]); ?>
</div>
