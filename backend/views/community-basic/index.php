<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Company;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommunityBasicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小区';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-index">

    <?php 
	$gridview = [
            ['class' => 'kartik\grid\SerialColumn', 'header' => '序号'],

            /*['attribute' =>'community_id',
			'width' => 'px',
			'hAlign' => 'center'],*/
		
		    ['attribute' =>'company',
			 'value' => 'c.name',
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => Company::find()
			             ->select(['name', 'id'])
			             ->indexBy('id')
			             ->orderBy('id')
			             ->column(),
			 'filterInputOptions' => [ 'placeholder' => '请选择……'],
			 'filterWidgetOptions' => [
	              'pluginOptions' => ['allowClear' => true],
	          ],
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/community-basic/community' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => Company::find()->select( [ 'name', 'id' ] )->orderBy( 'name' )->indexBy( 'id' )->column(),
			],
			'width' => '20%',
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

            ['class' => 'kartik\grid\ActionColumn',
			 'header' => '操<br />作',
			 'template' => Helper::filterActionColumn('{view}{update}{delete}')],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' =>'info', 'heading' => '小区列表',
				   'before' => Html::a('New', ['create'],['class' => 'btn btn-info'])],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
