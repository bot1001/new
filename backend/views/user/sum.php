<?php
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = '用户统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    th, td{
        text-align: center;
    }
</style>
<div class="site-login">
    <?= $this->render('_search', ['model' => new \app\models\UserAccount()]); ?>
    <?php
    $gridview = [
        ['class' => 'kartik\grid\SerialColumn',
            'header' => '序号'],
        ['attribute' => 'name',
            'label' => '公司',
            'contentOptions' => [ 'class' => 'text-left'],
            'pageSummary' => '合计：',],

        ['attribute' => 'community',
            'contentOptions' => [ 'class' => 'text-left'],
            'label' => '小区'],

        ['attribute' => 'sum',
            'value' => function($dataProvider){
                return $dataProvider['sum'].'例';
            },
            'label' => '注册量',
            'contentOptions' => [ 'class' => 'text-right'],
            'pageSummary' => true],

        ['class' => 'kartik\grid\CheckboxColumn'],
    ];

    echo GridView::widget([
            'dataProvider' => $dataProvider,
        'panel' => ['type' => 'info', 'heading' => '起止时间：'.$fromdate,
            'before' => Html::a('<span class="glyphicon glyphicon-refresh"></span>', 'sum', ['class' => 'btn btn-success'])],
        'columns' => $gridview,
        'showPageSummary' => true,
        'pjax' => true,
        'hover' => true
    ]);
    ?>
</div>