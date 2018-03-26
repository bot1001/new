<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '投诉/建议';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Ticket Basic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

      <?php
	  $gridColumn = [
		  ['attribute' =>'ticket_number',
		   'label' => '编号',
		   'hAlign' => 'center',
		   'width' => '100px'],
            
		  ['attribute' =>'community_name',
		   'label' => '小区',
		   'group' => true,
		   //'hAlign' => 'center',
		   'width' => '130px'],
		  ['attribute' =>'building_name',
		   'label' => '楼宇',
		   'group' => true,
		   'hAlign' => 'center',
		   'width' => '70px'],
		      //'ticket_id',
		  /*['attribute' => 'room_number',
		   'label' => '单元',
		   'group' => true,
		   'hAlign' => 'center',
		   'width' => '65px'],*/
		     //'ticket_id',
		  ['attribute' =>'room_name',
		   'label' => '房号',
		   'group' => true,
		   'hAlign' => 'center',
		   'width' => '65px'],
		      //'ticket_id',
		  ['attribute' =>'real_name',
		   'label' => '名字',
		   'group' => true,
		   'hAlign' => 'center',
		   'width' => '80px'],
		  ['attribute' =>'mobile_phone',
		   'label' => '手机号码',
		   'group' => true,
		   'hAlign' => 'center',
		   'width' => '10px'],
		  ['attribute' =>'explain1',
		   'label' => '详情',
		   'width' => '100px'],
          ['attribute' =>'create_time',
		   'format' => ['date','php:Y-m-d H:i:s'],
			'label' => '时间',
			'width' => '160px',
		   'hAlign' => 'center'],

		  ['attribute' =>'name',
		   'label' => '状态',
		   'group' => true,
		   'width' => '60px',
		   'hAlign' => 'center',],
		  /*['label'=>'更多',
		   'mergeHeader' => true,
           'format'=>'raw',
           'value' => function($model){
                $url = Yii::$app->urlManager->createUrl(['ticket/view','id' => 2.3]);
                return Html::a('more', $url); 
              }
           ],*/

            /*['class' => 'kartik\grid\ActionColumn',
			 'header' => '操作',
			 'width' => '30px',
			'template' => '{view}'],*/
        ];
	  echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'primary','heading' => '投诉列表'],
        'columns' => $gridColumn,
		'hover' => true
      ]); ?></td>
    
</div>
