<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Recharge */

$this->title = '更新: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Recharges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recharge-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
