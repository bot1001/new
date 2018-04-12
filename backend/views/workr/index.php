<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkRSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Work Rs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-r-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Work R', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
	$gridview = [
            ['class' => 'yii\grid\SerialColumn'],

            'work_number',
            ['attribute'=> 'community_id',
			'value' => 'c.community_name'],
	        'data.real_name',
            'account_superior',
            'work_status',
            'account_role',
            'account_status',

            ['class' => 'yii\grid\ActionColumn'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridview,
    ]); ?>
</div>
