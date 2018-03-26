<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RelationshipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = '管理房屋';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-relationship-realestate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
	$a = [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            'account_id',
            'realestate_id',

            ['class' => 'kartik\grid\ActionColumn',
			'template' =>'{update}{view}'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type'=> 'info','heading' => '管理房屋',
				   'before' =>Html::a('New', '#', ['class' => 'btn btn-info']) ],
        'columns' => $a,
    ]); ?>
</div>