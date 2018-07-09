<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommunityBasicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小区';
//$this->params['breadcrumbs'][] = $this->title;

//引入模态文件
echo $this->render('..\..\..\common\modal\modal.php'); ?>

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
			 'filter' => \app\models\Company::getCompany(),
			 'filterInputOptions' => [ 'placeholder' => '请选择……'],
			 'filterWidgetOptions' => [
	              'pluginOptions' => ['allowClear' => true],
	          ],
			 'class' => 'kartik\grid\EditableColumn',
			 'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/community-basic/community' ] ],
				'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
				'data' => \app\models\Company::getCompany(),
			],
                'readonly' => function ( $model, $key, $index, $widget ) {
                    return \app\models\Limit::limit($url='/community-basic/community') == 0 ; // 判断活动列是否可编辑
                },
			'width' => '20%',
			'hAlign' => 'center'],
		
		
		   ['attribute' =>'community_name',
             'readonly' => function(){
		         return \app\models\Limit::limit($url='/community-basic/community') == 0;
             },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'header' => '详情',
                'formOptions' => [ 'action' => [ '/community-basic/community' ] ],
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ],
			'hAlign' => 'center'],
		
            /*['attribute' =>'community_logo',
			'width' => 'px',
			'hAlign' => 'center'],*/
		
            ['attribute' =>'province_id',
			 'value' => 'province.area_name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \common\models\Area::getProvince(),
                'filterInputOptions' => [ 'placeholder' => '请选择……'],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],
		
		   ['attribute' =>'city_id',
			'value' => 'city.area_name',
             'filterType' => GridView::FILTER_SELECT2,
             'filter' => $city,
             'filterInputOptions' => [ 'placeholder' => '请选择……'],
             'filterWidgetOptions' => [
                 'pluginOptions' => ['allowClear' => true],
             ],
           ],
		
		   ['attribute' =>'area_id',
			'value' => 'area.area_name',
             'filterType' => GridView::FILTER_SELECT2,
             'filter' => $area,
             'filterInputOptions' => [ 'placeholder' => '请选择……'],
             'filterWidgetOptions' => [
                 'pluginOptions' => ['allowClear' => true],
             ],
           ],
		
            ['attribute' =>'community_address',
                'readonly' => function(){
                    return \app\models\Limit::limit($url='/community-basic/community') == 0;
                },
             'class' => 'kartik\grid\EditableColumn',
             'editableOptions' => [
                 'header' => '详情',
                 'formOptions' => [ 'action' => [ '/community-basic/community' ] ],
                 'inputType' => \kartik\editable\Editable::INPUT_TEXT,
             ],
			'hAlign' => 'center'],

           ['attribute' =>'sms',
            'value' => function($model){
		         $date = ['0' => '否', '1' => '是'];
		         return $date[$model->sms];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['0' => '否', '1' => '是'],
            'filterInputOptions' => [ 'placeholder' => '请选择……'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
               'readonly' => function(){
                   return \app\models\Limit::limit($url='/community-basic/community') == 0;
               },
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions' => [
                'header' => '详情',
                'formOptions' => [ 'action' => [ '/community-basic/community' ] ],
                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => ['0' => '否', '1' => '是'],
            ],
               //单元格背景色变换
               'contentOptions' => function ( $model ) {
                   return ( $model->sms == 0 ) ? [ 'class' => 'bg-info' ] : [];
               },
			'hAlign' => 'center'],
		
           ['attribute' =>'creater',
			'value' => 'sys.name',
			'hAlign' => 'center'],
		
            /* ['attribute' =>'community_latitude',
			'width' => 'px',
			'hAlign' => 'center'],*/

            ['class' => 'kartik\grid\ActionColumn',
			 'header' => '操<br />作',
			 'template' => Helper::filterActionColumn('{update}{delete}'),
			 'buttons' => [
				'update' => function ( $url, $model, $key ) {
					return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#common-modal',
						'data-url' => Url::toRoute( ['update', 'id' => $key] ),
						'data-title' => '编辑小区',
						'class' => 'pay',
					] );
				},
			],],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' =>'info', 'heading' => '小区列表',
				   'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
								'class' => 'btn btn-success pay',
								'data-toggle' => 'modal',
								'data-url' => Url::toRoute( 'create' ),
								'data-title' => '创建小区', //如果不设置子标题，默认使用大标题
								'data-target' => '#common-modal',
							] )],
		'hover' => true,
        'columns' => $gridview,
    ]); ?>
</div>
