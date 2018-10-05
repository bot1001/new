<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RechargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '费用充值';
$this->params['breadcrumbs'][] = $this->title;

//引入模态窗文件
echo $this->render('..\..\..\common\modal\modal.php');

?>
<style>
    th, td, tr{
        text-align: center;
    }
    .recharge-index{
        max-width: 800px;
    }
</style>

<script>
    function pay(){
        if(confirm('您确定充值吗？')){
            var realestate = document.getElementById('room')
            var keys = $("#grid").yiiGridView("getSelectedRows"); //获取选中列id
            if(keys.length == 0 || realestate.length == 0){
                alert( '选择有误，请重新选择！');
            }else{
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url:"add",
                    data:{id: keys, realestate: realestate.value},
                    // error: function () {
                    //     alert('操作失败，请联系管理员');
                    // }
                })
            }
        }
    }
</script>

<div class="recharge-index">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序<br />号'],
        ['class' => 'kartik\grid\checkBoxColumn',
            'name' => 'id'],

        ['attribute' => 'name',
            'contentOptions' => ['class' => ['text-left']]
        ],

//        ['attribute' => 'price',
//            'class' => 'kartik\grid\EditableColumn',
//            'editableOptions' => [
//                'formOptions' => [ 'action' => [ '/recharge/recharge' ] ],
//                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//            ],
//            'contentOptions' => ['class' => ['text-right']]
//            ],

        ['attribute' => 'type',
            'value' => function($model){
                $date = ['1' => '电费'];
                return $date[$model->type];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [ 1 => '电费'],
            'filterWidgetOptions' => [
                'pluginOptions' => [ 'allowClear' => true ],
            ],
            'filterInputOptions' => [ 'placeholder' => '请选择' ],],

        ['attribute' => 'create_time',
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
//                    'style'=>'width:160px',
                ],
            ],
        ],

        ['attribute' => 'creater',
            'mergeHeader' => true,
            'value' => 'user.name'],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => Helper::filterActionColumn('{update}'),
            'buttons' => [
                'update' => function($url, $model, $key){
                    return Html::a("<span class='glyphicon glyphicon-pencil'></span>", '#',[
                        'data-toggle' => 'modal',
                        'data-url' => Url::to(['update', 'id' => $key]),
                        'data-title' => '修改',
                        'class' => 'pay',
                        'data-target' => '#common-modal'
                    ]);
                },
            ],
            'header' => '操<br />作'],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['id' => 'grid'],
        'panel' => ['type' => 'info', 'heading' => '充值列表',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
                'class' => 'btn btn-success pay',
                'data-toggle' => 'modal',
                'data-url' => "create",
                'data-title' => '创建', //如果不设置子标题，默认使用大标题
                'data-target' => '#common-modal',
            ])
        ],
        'columns' => $gridview
    ]); ?>
</div>
