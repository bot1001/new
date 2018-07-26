<?php

use yii\ helpers\ Html;
use yii\ helpers\ArrayHelper;
use kartik\ grid\ GridView;
use yii\ widgets\ Pjax;
use app\ models\ Status;
use yii\ bootstrap\ Modal;
use yii\ helpers\ Url;
use kartik\daterange\DateRangePicker;
use mdm\admin\components\Helper;

Modal::begin( [
	'id' => 'update-modal',
	'header' => '<h4 class="modal-title">缴费管理</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
] );
$requestUpdateUrl = Url::toRoute( 'view' );
$importUrl = Url::toRoute( 'import' );

$updateJs = <<<JS
    $('.order').on('click', function () {
        $('.modal-title').html('订单详情');
        $.get('{$requestUpdateUrl}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

$updateJs = <<<JS
    $('.import').on('click', function () {
        $('.modal-title').html('费项导入');
        $.get('{$importUrl}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $updateJs );

Modal::end();

$script = <<<SCRIPT

//删除
$(".gridviewdelete").on("click", function () {
if(confirm('您确定要删除吗？')){
    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '/user-invoice/del',
            data: {ids:keys},
            type: 'post',
			success: function(is){
			    alert("成功删除："+is+"条！");
				location.reload();
			}
        })
    }
});

//缴费
$(".gridviewpay").on("click", function () {
if(confirm('您确定要缴费吗？')){
    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '/user-invoice/pay',
            data: {ids:keys},
            type: 'post',
            success: function (id) {
                t = JSON.parse(id);
                if (isset(t)) {
                    window.location.href= window.location.href;
                }
            },
        })
    }
});
SCRIPT;
$this->registerJs( $script );

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缴费管理';
//$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="user-invoice-index">

	<?php
	$message = Yii::$app->getSession()->getFlash('fail');
    $success = Yii::$app->getSession()->getFlash('success'); //获取提示信息
	if($message == 1){
		echo "<script>alert('选择内容不能为空，请重新选择！')</script>";
	}elseif($message == 2){
		echo "<script>alert('文件格式有误，请重新选择')</script>";
	}elseif($message == 3){
		echo "<script>alert('数据有误，请修改源数据')</script>";
	}elseif($message == 4){
		echo "<script>alert('费项选择有误，请重新选择')</script>";
	}elseif($message == 5){
		echo "<script>alert('关联小区错误，请联系管理员！')</script>";
	}

	?>
	<?php // echo $this->render('_search', ['model' => $searchModel, 'comm' => $comm]); ?>
	<?php //Pjax::begin(); ?>
	<?php
	
	$gridColumn = [
		/*['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],*/
		[ 'class' => 'kartik\grid\CheckboxColumn',
			'name' => 'id',
			'width' => '30px'
		],

		[ 'attribute' => 'invoice_id',
			//'footer' => Html::a('缴费', "javascript:void(0);", ['class' => 'btn btn-default gridviewpay ']),
			'width' => '70px',
			'hAlign' => 'center'
		],
		[ 'attribute' => 'community_id',
		  'value' => 'community.community_name',
	      'pageSummary' => '合计：',
	      'filterType' => GridView::FILTER_SELECT2,
	      'filter' => $comm,
	      'filterInputOptions' => [ 'placeholder' => '请选择' ],
	      'filterWidgetOptions' => [
	      	'pluginOptions' => [ 'allowClear' => true ],
	      ],
	      'hAlign' => 'center',
	      'width' => '150px'
		],

		[ 'attribute' => 'building_id',
		 'value' => 'building.building_name',
		 'filterType' => GridView::FILTER_SELECT2,
		 'filter' => $build,
		 'filterInputOptions' => ['placeholder' => ''],
		 'filterWidgetOptions' => [
	     	'pluginOptions' => ['allowClear' => true],
	     ],
			'label' => '楼宇',
			//'group' => true,
			//'hidden' => true,
			'hAlign' => 'center',
			'width' => '70px'
		],

		[ 'attribute' => 'number',
			'value' => 'room.room_number',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $number,
            'filterInputOptions' => ['placeholder' => ''],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            'label' => '单元',
			'hAlign' => 'center',
			'width' => '70px'
		],

        [ 'attribute' => 'room.room_name',
			//'value' => 'room.room_number',
			// 'group' => true,
			'hAlign' => 'center',
			'width' => '70px'
		],

        ['attribute' => 'name',
            'value' => 'room.owners_name',
            'hAlign' => 'center'],

		[ 'attribute' => 'year',
			'value' => function ( $model ) {
				return $model->year . '年';
			},
		 'filterType' => GridView::FILTER_SELECT2,
			'filter' => $y,
			'filterInputOptions' => [ 'placeholder' => '' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'hAlign' => 'center',
			'width' => '90px'
		],
		[ 'attribute' => 'month',
			'value' => function ( $model ) {
                return $model->month . '月';
			},
		 'filterType' => GridView::FILTER_SELECT2,
			'filter' => $m,
			'filterInputOptions' => [ 'placeholder' => '' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'hAlign' => 'center',
//			'width' => '100px'
		],
		//'year',

		[ 'attribute' => 'description',
			'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( $model->invoice_status != 0 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'header' => '详情',
				'formOptions' => [ 'action' => [ '/user-invoice/invoice' ] ],
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
				'options' => [
					'pluginOptions' => [ 'min' => 0, 'max' => 50 ]
				]
			],
			'hAlign' => 'left',
			'width' => ''
		],
		[ 'attribute' => 'invoice_amount',
//			'refreshGrid' => 'true',
			'pageSummary' => true,
			'class' => 'kartik\grid\EditableColumn',
			'readonly' => function ( $model, $key, $index, $widget ) {
				return ( $model->invoice_status != 0 ); // 判断活动列是否可编辑
			},
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/user-invoice/invoice' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_TEXT,
			],
			'hAlign' => 'center',
			'width' => '60px'
		],
		/*['attribute' => 'create_time',
			 'mergeHeader' => true,
			 //'group' => true,
			'format' => ['date','php:Y-m-d H:m:s'],
			 'hAlign' => 'center',
			'width' => '150px'
			],*/
		[ 'attribute' => 'order_id',
			'format' => 'raw',
			'value' => function ( $model ) {
				// $url = Yii::$app->urlManager->createUrl(['order/index1','order_id'=>$model->order_id]);
				return Html::a( $model->order_id, '#', [
					'data-toggle' => 'modal',
					'data-target' => '#update-modal',
					'class' => 'order',
				] );
			},
			//'group' => true,
			'hAlign' => 'center',
			'width' => '115px'
		],
		['attribute' => 'invoice_notes',
		 'value' => function($model){
	    	if($model->invoice_notes == ''){
	    		return '空';
	    	}else{
				return $model->invoice_notes;
			}	
	    },
		'class'=>'kartik\grid\EditableColumn',
		'editableOptions'=>[
            'formOptions'=>['action' => ['/user-invoice/invoice']], // point to the new action        
            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
		    ],
		],
		
		[ 'attribute' => 'payment_time',
//			'group' => true,
		    'value' => function($model){
	        	if(empty($model->payment_time)){
	        		return '';
	        	}else{
	        		return date('Y-m-d H:i:s', $model->payment_time);
	        	}
	        },
			//'mergeHeader' => true,
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
            'width' => '180px',
			'hAlign' => 'center',		
		],
		[ 'attribute' => 'invoice_status',
			'value' => function ( $model ) {
				$data = [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ];
				return $data[ $model[ 'invoice_status' ] ];
			},
			//'refreshGrid' => 'true',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ],
			'filterInputOptions' => [ 'placeholder' => '…' ],
			'filterWidgetOptions' => [
				'pluginOptions' => [ 'allowClear' => true ],
			],
			'class' => 'kartik\grid\EditableColumn',
			/*'readonly' => function($model, $key, $index, $widget) {
                 return ($model->invoice_status != 0); // 判断活动列是否可编辑
              },*/
			'editableOptions' => [
				'formOptions' => [ 'action' => [ '/user-invoice/invoice' ] ], // point to the new action        
				'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
				'data' => [ '0' => '欠费', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '8' => '优惠' ],
			],
			'hAlign' => 'center',
			'width' => ''
		],

		/*['attribute' => 'update_time',
			 'mergeHeader' => true,
			'value'=>
                function($model){
                    return  date('Y-m-d H:i:s',$model->update_time);   //主要通过此种方式实现
                },
			 'hAlign' => 'center',
			'width' => '170px'
			],*/

		['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br />作',
			'template' => Helper::filterActionColumn('{delete}'),
            'width' => '25px'
			],
	];
	echo GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'options' => [ 'id' => 'grid' ],
		//'showFooter' => true,
		'showPageSummary' => true,
		'panel' => [ 'type' => 'info', 'heading' => '缴费',
			'before' => Html::a( '<span class="glyphicon glyphicon-cloud-upload"></span>', 'import', [
				'data-toggle' => 'modal',
				'data-target' => '#update-modal',
				'class' => 'btn btn-info import',
			] ) . ' ' .
			Html::a( '缴费', "javascript:void(0);", [ 'class' => 'btn btn-default gridviewpay ' ] ),
		],

		'toolbar' => [
			[ 'content' =>
				Html::a( '<span class="glyphicon glyphicon-trash"></span>', "javascript:void(0);", [ 'class' => 'btn btn-danger gridviewdelete ' ] ) . ' ' .
				Html::a( '统计', [ 'sum' ], [ 'class' => 'btn btn-success' ] )
			],
			'{toggleData}',
			'{export}'
		],
		'columns' => $gridColumn,
		'hover' => true
	] );
	?>
	<?php //Pjax::end(); ?>
</div>