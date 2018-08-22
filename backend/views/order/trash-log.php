<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '作废记录';
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>

<div class="order-basic-index">

	<?php
	$gridColumn = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序号'
		],

		[ 'attribute' => 'address',
            'contentOptions' => ['class' => 'text-left'],
            'label' => '地址',
            ],
        ['attribute'=>'name',
            'label' => '下单人',
            'contentOptions' => ['class' => 'text-left'],],

		[ 'attribute' => 'type',
			'group' => true,
			'value' => function ( $model ) {
				return $model['type'] == 1 ? '物业' : '商城';
			},
            'label' => '类型',
			'hAlign' => 'center',
			'width' => '50px'
		],

		[ 'attribute' => 'order_id',
			'label' => '订单编号',
			'hAlign' => 'center',
			'width' => '130px'
		],

		[ 'attribute' => 'time',
		  'mergeHeader' => true,
		  'value' =>
			    function ( $model ) {
			    	return date( 'Y-m-d H:i:s', $model['time'] ); //主要通过此种方式实现
			    },
            'label' => '时间',
		  'hAlign' => 'center',
		],

		[ 'attribute' => 'way',
			'value' => function ( $model ) {
				$e = [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行', 8 => '优惠' ];
				if ( empty( $model[ 'way' ] ) ) {
					return '';
				} else {
					return $e[ $model[ 'way' ] ];
				};
			},
            'label' => '收款方式',
			'hAlign' => 'center',
			'width' => '100px'
		],

        ['attribute' =>  'verify',
            'value' => function($model){
                $date = [ '0' => '否', '1' => '是'];
                return $date[$model['verify']];
            },
            'label' => '财务验证',
            'hAlign' => 'center'],

		[ 'attribute' => 'amount',
            'contentOptions' => ['class' => 'text-right'],
            'label' => '金额',
			'pageSummary' => true,
			'width' => '70px',
		],

        ['attribute'=>'action',
            'label' => '操作人',
            'contentOptions' => ['class' => 'text-left'],]
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'showPageSummary' => true,
		'panel' => [ 'type' => 'info', 'heading' => '作废记录' ],
		'columns' => $gridColumn,
		'hover' => true
	] );
	?>
</div>