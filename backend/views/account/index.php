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

    <h1><?php // Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('New', ['create','account_id' => $k], ['class' => 'btn btn-info']) ?>
    </p>
    <?php
	$gridColumn = [
		['class' => 'yii\grid\SerialColumn',
		'header' => '序号'],
		['attribute' => 'password',
		'hAlign' => 'center'],
		['attribute' => 'mobile_phone',
		'hAlign' => 'center'], 
		/*['class' => 'yii\grid\ActionColumn',
		'header' => '操作'],*/
	];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
    ]); ?>
</div>
