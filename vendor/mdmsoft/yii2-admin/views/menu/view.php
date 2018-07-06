<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'menuParent.name:text:Parent',
            'name',
            'route',
            'order',
        ],
    ])
    ?>

    <p align="center">
        <?php // echo Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除吗?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

</div>
