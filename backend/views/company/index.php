<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公司';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <?php 
	
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序号'],
            'name',
            'creator',
            'create_time:datetime',
            'property',
		['class' => 'kartik\grid\ActionColumn',
		 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
		'header' => '操作'],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '公司',
				   'before' => Html::a('New', ['create'], ['class' => 'btn btn-info'])],
        'columns' => $gridview,
    ]); ?>
</div>
