<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CostName */

$this->title = $model->cost_name;
$this->params['breadcrumbs'][] = ['label' => '费项列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cost_name, 'url' => ['view', 'id' => $model->cost_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cost-name-update">

    <?= $this->render('_form', [
        'model' => $model,
	    'role' =>$role
    ]) ?>

</div>
