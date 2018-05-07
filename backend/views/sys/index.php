<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SysCommunitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户关联小区';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-community-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
	$grid = [
            ['class' => 'kartik\grid\SerialColumn','header' => '序<br />号'],

            ['attribute'=> 'company',
			 'value' => 'com.name',
			 'filterType' => GridView::FILTER_SELECT2,
		    'filter' => $company,
		    'filterInputOptions' => [ 'placeholder' => '请选择' ],
		    'filterWidgetOptions' => [
		    	'pluginOptions' => [ 'allowClear' => true ],
		    ],
			 'label' => '公司'
			],
		
            ['attribute'=> 'name',
			'value' => 'sysUser.name',
			'label' => '用户名'],
		
            ['attribute'=> 'community_id',],

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => '{update}{delete}',
			 'header' => '操<br />作'],
        ];
		
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '关联小区列表'/*,
				   'before' => Html::a('NEW', ['/sys/create'], ['class' => 'btn btn-primary'])*/],
        'columns' => $grid,
		'hover' => true,
    ]); ?>
</div>
