<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
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
       if(keys.length == 0){
           alert("选择有误，请重新选择！");
       }else{
           $.ajax({
               url: '/user-invoice/del',
               data: {ids:keys},
               type: 'post',
	   		     success: function(is){
	   		         alert("成功删除："+is+"条！");
	   		     	  location.reload();
	   		     },
	   		     error:function(){
	   		        alert("您无此权限，请联系管理员！");
	   		     }
           })
       }     
    }
});

//缴费
$(".gridviewpay").on("click", function () {
    if(confirm('您确定要缴费吗？')){
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if(keys.length == 0){
            alert('选择不能为空！');
        }else{
            $.ajax({
                url: '/user-invoice/pay',
                data: {ids:keys},
                type: 'post',
                //多余代码起
                success: function (id) {
                    t = JSON.parse(id);
                    console.log(id);
                    if (isset(t)) {
                        window.location.href= window.location.href;
                    }
                },
                //多余代码止
            })
        }     
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

<style>
    th, td{
        text-align: center;
    }
</style>
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
	<?php
	$gridColumn = [
		/*['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br />号'],*/
		[ 'class' => 'kartik\grid\CheckboxColumn',
			'name' => 'id',
			'width' => '30px'
		],

		[ 'attribute' => 'invoice_id',
			'pageSummary' => Html::a('<span class="glyphicon glyphicon-credit-card" style="font-size: 25px; color: green"></span>', "javascript:void(0);", ['class' => 'gridviewpay', 'id' => 'jf', 'title' => '立即缴费']),
			'width' => '70px',
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
            'contentOptions' => ['class' => 'text-left'],
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
			'width' => '70px'
		],

		[ 'attribute' => 'number',
			'value' => function($model){
                if(!is_numeric($model->room->room_number))
                {
                    return $model->room->room_number.'座';
                }else{
                    return $model->room->room_number;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $number,
            'filterInputOptions' => ['placeholder' => ''],
            'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
            ],
            'label' => '单元',
			'width' => '70px'
		],

        [ 'attribute' => 'room',
			'value' => 'room.room_name',
			// 'group' => true,
            'label' => '房号',
			'width' => '70px'
		],

        ['attribute' => 'name',
            'value' => 'room.owners_name',
            'contentOptions' => ['class' => 'text-left'],],

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
            'contentOptions' => ['class' => 'text-left'],
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
				'inputType' => \kartik\editable\Editable::INPUT_TEXT,
			],
            'contentOptions' => ['class' => 'text-right'],
			'width' => '60px'
		],
		/*['attribute' => 'create_time',
			 'mergeHeader' => true,
			 //'group' => true,
			'format' => ['date','php:Y-m-d H:m:s'],
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
            'readonly' => function($model){
                return $model->invoice_status != 0;
            },
		'editableOptions'=>[
            'formOptions'=>['action' => ['/user-invoice/invoice']], // point to the new action        
            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
		    ],
            'contentOptions' => ['class' => 'text-left'],
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
            /*'class' => 'kartik\grid\EditableColumn',
          /*'readonly' => function($model, $key, $index, $widget) {
                 return ($model->invoice_status != 0); // 判断活动列是否可编辑
              },
            'editableOptions' => [
                'formOptions' => [ 'action' => [ '/user-invoice/invoice' ] ], // point to the new action
                'inputType' => \kartik\ editable\ Editable::INPUT_DROPDOWN_LIST,
                'data' => [ '0' => '欠费', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '8' => '优惠' ],
            ],*/
			'width' => ''
		],

		/*['attribute' => 'update_time',
			 'mergeHeader' => true,
			'value'=>
                function($model){
                    return  date('Y-m-d H:i:s',$model->update_time);   //主要通过此种方式实现
                },
			'width' => '170px'
			],

		['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br />作',
			'template' => Helper::filterActionColumn('{delete}'),
            'width' => '25px'
			],*/
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
</div>