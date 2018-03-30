<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = 'Create House Info';
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
