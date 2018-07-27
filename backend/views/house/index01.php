<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\houseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '房屋信息';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-index">

    <?php
	$gridview = [
		
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序号'],

            ['attribute'=> 'name',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'phone',
			'hAlign' => 'center',
			'width' => '150px'],
		
            ['attribute'=> 'IDcard',
			'hAlign' => 'center',
			'width' => '200px'],
		
            ['attribute'=> 'status',
			 'value' => function($model){
	         	$date = ['0' => '停用', '1' => '在用'];
	         	return $date[$model->status];
	         },
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'address',
			'width' => 'px'],
		
            ['attribute'=> 'property',
			'hAlign' => 'center',
			'width' => 'px'],
		
//            ['class' => 'kartik\grid\ActionColumn',
//			 'template' => Helper::filterActionColumn('{update}{delete}'),
//			 'header' => '操作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => ['type' => 'info', 'heading' => $room],
		'hover' => true,
        'toolbar' => false,
        'columns' => $gridview,
    ]); ?>
</div>
<div align="center">
    <?= Html::a('<span class = "glyphicon glyphicon-plus"></span>', ['/house/c', 'id'=>$id, 'room'=> $room], ['class'=> 'btn btn-info']) ?>
</div>
