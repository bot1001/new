<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelation */

$this->title = '更新';
$this->params['breadcrumbs'][] = ['label' => '费项关联', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cost-relation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 