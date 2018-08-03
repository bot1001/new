<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
//$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    th, td{
        text-align: center;
    }
</style>
<div class="user-account-index">

       <?php
    $get = $_GET;
    if(isset($get['UserSearch'])){
        $user = $get['UserSearch'];
    }else{
        $user = '';
    }

	     $gridColumn = [     
            ['class' => 'kartik\grid\SerialColumn',
			'header' =>'序<br />号'],

	        ['attribute' => 'community_name',
			 'filterType'=>GridView::FILTER_SELECT2,
			 'filter' => $comm,
			 'filterInputOptions'=>['placeholder'=>'请选择'],
			 'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
             ],
                'contentOptions' => ['class' => 'text-left'],
			 // 'hidden'=>true,
			'label' => '小区',
			 //'group' =>true,
			'width' => '150px'],
	    	['attribute' => 'building_name',
			 'width'=>'80px',
	    	 'label' => '楼宇'],
	    	['attribute' => 'room_number',
			 'width'=>'80px',
	    	 'label' => '单元'],
	    	['attribute' => 'room_name',
			 'width'=>'80px',
	    	 'label' => '房号'],
	    	['attribute' => 'real_name',
                'contentOptions' => ['class' => 'text-left'],
	    	 'label' => '姓名'],
	    	 
	    	['attribute' => 'mobile_phone',
	    	 'label' => '手机号码'],
	    	['attribute' => 'reg_time',
	    	 'format' =>['date','php:Y-m-d H:i:s'],
			 'filterType' =>GridView::FILTER_DATE_RANGE,//'\kartik\daterange\DateRangePicker',//过滤的插件，
             'filterWidgetOptions'=>[
                 'pluginOptions'=>[
                     'autoUpdateOnInit'=>false,
                     //'showWeekNumbers' => false,
                     'useWithAddon'=>true,
                     'convertFormat'=>true,
                     'timePicker'=>false,
                     'locale'=>[
                         'format' => 'YYYY-MM-DD',
                         'separator'=>' to ',
                         'applyLabel' => '确定',
                         'cancelLabel' => '取消',
                         'fromLabel' => '起始时间',
                         'toLabel' => '结束时间',
                         //'daysOfWeek'=>false,
                     ],
                     'opens'=>'center',
                     //起止时间的最大间隔
                     'dateLimit' =>[
                         'days' => 400
                     ]
                 ],
                 'options' => [
                     'placeholder' => '请选择...',
                     //'style'=>'width:200px',
                 ],
		     ],
	    	 'label' => '注册时间'],
	    	['attribute' => 'account_role',
			 'filterType' => GridView::FILTER_SELECT2,
	    	 'filter' => [0=> '业主', 1=>'物业'],
		     'filterInputOptions'=>['placeholder'=>'请选择'],
			 'filterWidgetOptions'=>[
                             'pluginOptions'=>['allowClear'=>true],
		                 ],
		   /* 'value' => function($model) {
			$role = [0=>'业主',1 => '物业'];
                      return $dataProvider->account_role;
                      },*/
		  	'label' => '角色'],
		    ['attribute' => 'name',
		   'filterType'=>GridView::FILTER_SELECT2,
		   'filter' => Status::find()
		            -> select(['name'])
		            -> where(['property'=>'1'])
		            -> orderBy('name')
	                -> indexBy('name')
	                -> column(),
			 'filterInputOptions'=>['placeholder'=>'请选择'],
			 'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
             ],
			'label' => '状态'],

            /*['class' => 'kartik\grid\CheckBoxColumn',
			 'name'=>'id',
			 'template' => '{delete}',
			'header' => '操<br />作'],*/
        ];
	     echo GridView::widget([
			'options' =>['id'=>'grid'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
			'panel' => ['type' => 'info','heading' => '用户管理',
                'before' => Html::a('统计', ['sum', 'user' => $user], ['class' => 'btn btn-success'])],
            'columns' => $gridColumn,
			 'pjax' => true,
			 'hover' => true
        ]); 	
	?>
</div>
