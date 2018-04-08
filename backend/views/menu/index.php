<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->title = Yii::t('rbac-admin', '系统菜单');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
   
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => 'info', 'heading' => '系统菜单',
				   'before' => Html::a(Yii::t('rbac-admin', '创建'), ['create'], ['class' => 'btn btn-primary'])],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn','header' => '序<br />号'],
            ['attribute' => 'name',],
            [
                'attribute' => 'menuParent.name',
                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                    'class' => 'form-control', 'id' => null
                ]),	
                'label' => Yii::t('rbac-admin', '父级'),
            ],
            ['attribute' => 'route',],
            ['attribute' => 'order',],
		    ['attribute' => 'data',
			 'label' => '标签',
			 'mergeHeader' => true],
            ['class' => 'kartik\grid\ActionColumn',
			 'template' => Helper::filterActionColumn('{view}{update}{delete}'),
			 'header' => '操<br />作'],
        ],
    ]);
    ?>
<?php Pjax::end(); ?>

</div>
