<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信模板';
$this->params['breadcrumbs'][] = $this->title;

//引入模态文件
echo $this->render('..\..\..\common\modal\modal.php');
?>
<div class="sms-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn', 'header' => '序<br />号'],

        'sign_name',
        'sms',
        ['attribute' => 'count', 'hAlign' => 'center'],
        ['attribute' => 'name',
            'value' => 'sys.name',
            'hAlign' => 'center'
        ],

        ['attribute' => 'status',
            'value' => function($model){
                $date = ['0' => '否', '1' => '是'];
                return $date[$model->status];
            },
            'hAlign' => 'center'],

        ['attribute' => 'create_time',
            'value'=> function($model){
                return date('Y-m-d H:i:s');
            },
            'hAlign' => 'center'],
        'property',

        ['class' => 'kartik\grid\ActionColumn',
            'template' => Helper::filterActionColumn('{update}&nbsp;&nbsp;&nbsp;{delete}'),
            'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#',[
                          'class' => 'pay',
                          'data-toggle' => 'modal',
                          'data-url' => Url::to(['update', 'id' => $key]),
                          'data-title' => '修改',
                          'data-target' => '#common-modal',
        ]);
                    }
            ],
            'header' => '操<br />作'],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => ['type' => 'info', 'heading' => '短信模板',
            'before' => Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
                'class' => 'btn btn-success pay',
                'data-toggle' => 'modal',
                'data-url' => 'create',
                'data-title' => '添加模板', //如果不设置子标题，默认使用大标题
                'data-target' => '#common-modal',
            ])
        ],
        'columns' => $gridview,
    ]); ?>
</div>
