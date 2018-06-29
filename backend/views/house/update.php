<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = '更新: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->house_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="house-info-update">

    <?= $this->render('_form', [
        'model' => $model,
	    'community' => $community,
		'building' => $building
    ]) ?>

</div>
