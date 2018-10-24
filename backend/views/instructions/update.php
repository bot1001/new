<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Instructions */

$this->title = '更新: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Instructions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="instructions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
