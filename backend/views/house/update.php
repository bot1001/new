<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = 'Update House Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->house_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="house-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
