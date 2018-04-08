<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', '角色');
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<div class="role-index">
   
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'primary', 'heading' => '角色列表',
				   'before' => Html::a(Yii::t('rbac-admin', 'New'), ['create'], ['class' => 'btn btn-success']) ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
			'header' => '序<br >号'],
            [
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', '名称'),
            ],
            [
                'attribute' => 'ruleName',
                'label' => Yii::t('rbac-admin', '路由规则'),
                'filter' => $rules
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', '描述'),
            ],
            ['class' => 'kartik\grid\ActionColumn',
			'header' => '操<br >作'],
        ],
    ])
    ?>

</div>
