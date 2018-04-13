<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$script = <<<SCRIPT

//缴费
$(".gridviewdelete").on("click", function () {
if(confirm('您确定要删除吗？')){
    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '/workr/del',
            data: {ids:keys},
            type: 'post',
        })
    }
});
SCRIPT;
$this->registerJs( $script );

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkRSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '关联小区';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-r-index">
    
    <?php
	   $message = Yii::$app->getSession()->getFlash('fail');
	   if($message == '1'){
		   echo "<script>alert('测试')</script>";
	   }
	?>

    <?php
	$grid = [
            ['class' => 'kartik\grid\CheckboxColumn','name' => 'id'],
		
            ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],

            ['attribute' => 'community',
			 'value' => 'c.community_name',
			 'filterType' => GridView::FILTER_SELECT2,
		     'filter' => $community,
		     'filterInputOptions' => [ 'placeholder' => '请选择' ],
		     'filterWidgetOptions' => [
		     	'pluginOptions' => [ 'allowClear' => true ],
		     ],
			'label' => '关联小区'],
		
		    ['attribute'=> 'name',
			'value' => 'account.user_name',
			'label' => '用户名'],
		
		    ['attribute'=> 'phone',
			'value' => 'account.mobile_phone',
			'label' => '手机号码'],
		
            ['attribute'=> 'account_status',
			 'value' => function($model){
	         	$date = [1 => '正常', 2 => '删除', 3 => '锁定'];
	         	return $date[$model->account_status];
	         },
			 'filterType' => GridView::FILTER_SELECT2,
			 'filter' => [1 => '正常', 2 => '删除', 3 => '锁定'],
			 'filterInputOptions' => ['placeholder' => '请选择……'],
			 'filterWidgetOptions' => [
	         	'pluginOptions' => ['allowClear' => true],
	         ],
			'label' => '状态'],

<<<<<<< HEAD
<<<<<<< HEAD
            ['class' => 'kartik\grid\ActionColumn',
			'template' => '{delete}',
			'header' => '操<br />作'],
        ];
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => [ 'id' => 'grid' ],
		'panel' => ['type' => 'info', 'heading' => '关联小区',
				   'before' => Html::a('New' ,['create'], ['class' => 'btn btn-primary'])],
		'toolbar' => [
			[ 'content' =>
				Html::a( '删除', "javascript:void(0);", [ 'class' => 'btn btn-danger gridviewdelete ' ] )
			]
		],
		'hover' => true,
        'columns' => $grid,
=======
=======
>>>>>>> master
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
<<<<<<< HEAD
>>>>>>> 16188838b6ee44d883587d5c326216c461491d06
=======
>>>>>>> master
    ]); ?>
</div>
