<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommunityBasicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小区';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Community Basic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
	$gridview = [
            //['class' => 'kartik\grid\SerialColumn'],

            ['attribute' =>'community_id',
			'width' => 'px',
			'hAlign' => 'center'],
		
		   ['attribute' =>'community_name',
			'width' => 'px',
			'hAlign' => 'center'],
		
            ['attribute' =>'community_logo',
			'width' => 'px',
			'hAlign' => 'center'],
		
            /*['attribute' =>'province_id',
			'width' => 'px',
			'hAlign' => 'center'],
		
            ['attribute' =>'city_id',
			'width' => 'px',
			'hAlign' => 'center'],
		
            ['attribute' =>'area_id',
			'width' => 'px',
			'hAlign' => 'center'],*/
		
            ['attribute' =>'community_address',
			'width' => 'px',
			'hAlign' => 'center'],
		
            ['attribute' =>'community_longitude',
			'width' => 'px',
			'hAlign' => 'center'],
		
            ['attribute' =>'community_latitude',
			'width' => 'px',
			'hAlign' => 'center'],

            //['class' => 'kartik\grid\ActionColumn',],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'panel' => ['type' =>'primary', 'heading' => '小区列表',
				   'before' => Html::a('New', ['create'],['class' => 'btn btn-primary'])],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
