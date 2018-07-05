<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'building' => $building
    ]) ?>

</div>
