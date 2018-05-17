<?php

use yii\helpers\Html;
use kartik\grid\GridView;

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
			'header' => '序<br />号'],
		
            /*['attribute'=> 'house_id',
			'hAlign' => 'center',
			'width' => ''],*/
		
            ['attribute'=> 'community',
			 'value' => 'c.community_name',
			 'label' => '小区',
			'hAlign' => 'center',
			'width' => 'px'],
		
		    ['attribute'=> 'building',
			 'value' => 'b.building_name',
			 'label' => '楼宇',
			'hAlign' => 'center',
			'width' => 'px'],
		
		    ['attribute'=> 'number',
			 'value' => 're.room_number',
			 'label' => '单元',
			'hAlign' => 'center',
			'width' => 'px'],
		
		   ['attribute'=> 'room_name',
			 'value' => 're.room_name',
			 'label' => '房号',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'name',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'phone',
			'hAlign' => 'center',
			'width' => '150px'],
		
            ['attribute'=> 'IDcard',
			'hAlign' => 'center',
			'width' => '200px'],
		
            /*['attribute'=> 'creater',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'create',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'update',
			'hAlign' => 'center',
			'width' => 'px'],*/
		
            ['attribute'=> 'status',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['attribute'=> 'address',
			'hAlign' => 'center',
			'width' => 'px'],
		
            /*['attribute'=> 'politics',
			'hAlign' => 'center',
			'width' => 'px'],*/
		
            ['attribute'=> 'property',
			'hAlign' => 'center',
			'width' => 'px'],
		
            ['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '业主资料',
				   'before' => Html::a('New', ['create'], ['class' => 'btn btn-info btn-sm'])],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
