<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */

$this->title = 'Update Advertising: ' . $model->ad_id;
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ad_id, 'url' => ['view', 'id' => $model->ad_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertising-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
