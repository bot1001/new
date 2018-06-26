<?php
use kartik\tree\TreeView;
use common\models\Tree;
use kartik\tree\Module;
use yii\helpers\Url;

echo TreeView::widget([
    // single query fetch to render the tree
    'query'             => Tree::find()->addOrderBy('root, lft'), 
    'headingOptions'    => ['label' => '房屋管理'],
    'isAdmin'           => 0,                       // optional (toggle to enable admin mode)
    'displayValue'      => 1,                           // initial display value
	'nodeActions' => [
        Module::NODE_MANAGE => Url::to(['treemanager-router', 'router' => 'index']),
    ],
    //'softDelete'      => true,                        // normally not needed to change
    //'cacheSettings'   => ['enableCache' => true]      // normally not needed to change

    'headingOptions'    => ['label' => '房屋管理'],
    'nodeView'          => '/community-realestate/home',
    'isAdmin'           => false,
    'rootOptions'       => ['label' => '小区'],
    'toolbar'           => [
        TreeView::BTN_REFRESH => false,
        TreeView::BTN_CREATE => false,
        TreeView::BTN_CREATE_ROOT => false,
        TreeView::BTN_REMOVE => false,
        TreeView::BTN_SEPARATOR => false,
        TreeView::BTN_MOVE_UP => false,
        TreeView::BTN_MOVE_DOWN => false,
        TreeView::BTN_MOVE_LEFT => false,
        TreeView::BTN_MOVE_RIGHT => false,
        TreeView::BTN_SEPARATOR => false,
    ],
]) ?>