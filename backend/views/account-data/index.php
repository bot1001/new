<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '注册时间';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-data-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create User Data', ['#'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
	$a = [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'account_id',
            'real_name',
            //'gender',
            //'face_path',
            // 'province_id',
            // 'city_id',
            // 'area_id',
            // 'reg_time:datetime',
            // 'nickname',
            // 'property',

            ['class' => 'yii\grid\ActionColumn',
			'template' => '{view}'],
        ];
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' =>'primary','heading' => '注册时间'
				   ],
        'columns' => $a,
    ]); ?>
</div>
