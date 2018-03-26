<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */

$this->title = '更新'.'-' . $model->building_id;
$this->params['breadcrumbs'][] = ['label' => '楼宇列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->building_id, 'url' => ['view', 'id' => $model->building_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="community-building-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
