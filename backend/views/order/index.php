<?php

use yii\ helpers\ Html;
use kartik\ grid\ GridView;
use yii\ bootstrap\ Modal;
use yii\ helpers\ Url;
use yii\widgets\Pjax;
use app\ models\ Status;
use app\ models\ OrderRelationshipAddress;
use app\ models\ OrderProducts;
use app\ models\ UserInvoice;
use app\ models\ CommunityRealestate;
use app\ models\ CommunityBuilding;
use app\ models\ CommunityBasic;
use kartik\daterange\DateRangePicker;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title"><center>订单详情</center></h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$V_Url = Url::toRoute( 'view' );

$vJs = <<<JS
    $('.view').on('click', function () {
        $.get('{$V_Url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $vJs );

Modal::end();


/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单管理';
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="order-basic-index">

	<?php 
		$message = Yii::$app->getSession()->getFlash('m');
		if($message == 1){
	        echo "<script>alert('打印数据为空，请确认订单信息！')</script>";
	        }
	        elseif ( $message == 2 ) {
	        		echo "<script>alert('订单未支付，无法打印！')</script>";
	        	} elseif ( Yii::$app->getSession()->hasFlash( 'cancel' ) ) {
	        			$a = Yii::$app->getSession()->getFlash( 'cancel' );
	        			echo "<script>alert('$a')</script>";
	        		} elseif ( Yii::$app->getSession()->hasFlash( 'm_order' ) ) {
	        				$m_order = Yii::$app->getSession()->getFlash( 'm_order' );
	        				echo "<script>alert('未支付订单，暂无更多信息！')</script>";
	        			} elseif ( Yii::$app->getSession()->hasFlash( 'can' ) ) {
			$m_order = Yii::$app->getSession()->getFlash('can');
			echo "<script>alert('订单超时，请重新下单！')</script>";
		}
		?>	
		
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
	<?php
	$gridColumn = [
		[ 'class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'
		],

		//'id',
		//'account_id',
		[ 'attribute' => 'order0.address'],
		[ 'attribute' => 'order0.name',
			'format' => 'raw',
			'value' => function ( $model ) {
                $name = OrderRelationshipAddress::find()->select( 'name' )->where( [ 'order_id' => $model->order_id ] )->asArray()->one();
	            if($model->status == '2'){
                    $url = Yii::$app->urlManager->createUrl( [ 'order/print', 'order_id' => $model->order_id, 'amount' => $model->order_amount ] );
                    return Html::a( $name[ 'name' ], $url );
                }else{
	                return $name['name'];
                }
			},
			'hAlign' => 'center',
			'width' => '75px'
		],
		[ 'attribute' => 'order0.mobile_phone',
			'hAlign' => 'center',
			'width' => '110px'
		],

		[ 'attribute' => 'order_type',
			'group' => true,
			//'groupedRow'=>true,                    // move grouped column to a single grouped row
			// 'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
			// 'groupEvenCssClass'=>'kv-grouped-row',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => [ '1' => '物业', '2' => '商城' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'filterInputOptions' => [ 'placeholder' => '' ],
			'value' => function ( $model ) {
				return $model->order_type == 1 ? '物业' : '商城';
			},
			'hAlign' => 'center',
			'width' => '50px'
		],

		[ 'attribute' => 'order_id',
			'label' => '订单编号',
			//'mergeHeader' => true,
			'format' => 'raw',
			'value' => function ( $model ) {
	            if($model->status == 2){
                    $url = Yii::$app->urlManager->createUrl( [ 'user-invoice/index', 'order_id' => $model->order_id, 'order' => 'order' ] );
                    return Html::a( $model->order_id, $url );
                }else{
	                return $model->order_id;
                }
			},
			'hAlign' => 'center',
			'width' => '130px'
		],

		[ 'attribute' => 'create_time',
		  'mergeHeader' => true,
		  'value' =>
			    function ( $model ) {
			    	return date( 'Y-m-d H:i:s', $model->create_time ); //主要通过此种方式实现
			    },
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
                 'style'=>'width:160px',
             ],
		 ],
		  'hAlign' => 'center',
		  //'width' => '160px'
		],

		[ 'attribute' => 'payment_time',
			//'mergeHeader' => true,
		    'value' => function($model){
		        if(empty($model->payment_time)){
		        	return '';
		        }else{
		                return date('Y-m-d H:i:s', $model->payment_time);
		        }
	        },
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
                        'days' => 90
                    ]
                ],
                'options' => [
                    'placeholder' => '请选择...',
                    'style'=>'width:200px',
                ],
		    ],
			'hAlign' => 'center',
			'width' => '160px'
		],
		[ 'attribute' => 'payment_gateway',
			//'group' => true,
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行', 8 => '优惠' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'value' => function ( $model ) {
				$e = [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行', 8 => '优惠' ];
				if ( empty( $model[ 'payment_gateway' ] ) ) {
					return '';
				} else {
					return $e[ $model[ 'payment_gateway' ] ];
				};
			},
			'hAlign' => 'center',
			'width' => '100px'
		],
        ['attribute' =>  'check',
            'value' => function($model){
                $date = [ '0' => '否', '1' => '是'];
                return $date[$model->check];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [ '0' => '否', '1' => '是'],
            'filterInputOptions' =>  ['placeholder' =>'请选择'],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            'class' => 'kartik\grid\EditableColumn',
            // 判断活动列是否可编辑
            'readonly' => function ( $model, $key, $index, $widget ) {
                return (\app\models\Limit::limit($url = 'order/order') == 0  || $model->check == 1 || $model->status != 2);
            },
            'editableOptions' => [
                'formOptions' => [ 'action' => [ '/order/order' ] ], // point to the new action
                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data' => [ '0' => '否', '1' => '是'],
            ],
            'contentOptions' =>
            function($model){
                return ( $model->check == 0 ) ? [ 'class' => 'bg-warning' ] : []; // 根据值改变底色
            },
            'hAlign' => 'center'],
		/*['attribute' => 'payment_number',
		 'width' => '200px',
		 'hAlign' => 'center'],*/
		[ 'attribute' => 'order_amount',
			'pageSummary' => true,
			'width' => '70px',
			'hAlign' => 'center'
		],
		/* ['attribute' => 'invoice_id',
			'hAlign' => 'center'],*/
		[ 'attribute' => 'status',
			'value' => 'status0.name',
			//'refreshGrid' => true,
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => Status::find()->select( [ 'name', 'order_basic_status' ] )->orderBy( 'name' )->indexBy( 'order_basic_status' )->column(),
			'filterInputOptions' => [ 'placeholder' => '请选择' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'class' => 'kartik\grid\EditableColumn',
			// 判断活动列是否可编辑
			'readonly' => function ( $model, $key, $index, $widget ) {
                return ( \app\models\Limit::limit($url = 'order/order') != 1 || $model->status != 1 );
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/order/order' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => [ '1' => '未支付', '2' => '已支付', '3' => '已取消', '4' => '送货中', '5' => '已签收' ],
			],
			'contentOptions' =>
			    function ( $model ) {
			    	return ( $model ->status == 2 ) ? [ 'class' => 'bg-info' ] : []; // 根据值改变底色
			    },
			'width' => '90px',
			'hAlign' => 'center'
		],

		[ 'class' => 'kartik\grid\ActionColumn',
			'template' => '{view}',
			'buttons' => [
				'view' => function ( $url, $model, $key ) {
					return Html::a( 'More', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#view-modal', //modal 名字
						'class' => 'view', //操作名
						'data-id' => $key,
					] );
				},
			],
			'width' => '30px',
			'header' => '操<br />作'
		],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'showPageSummary' => true,
		'panel' => [ 'type' => 'info', 'heading' => '订单管理',
				   //'before' => Html::a('你好')
				   ],
		'columns' => $gridColumn,
		'hover' => true
	] );
	?>
	<?php Pjax::end(); ?>
</div>