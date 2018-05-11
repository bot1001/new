<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CostName */

$this->title = 'New';
$this->params['breadcrumbs'][] = ['label' => '费项列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-name-create">

    <?= $this->render('_form', [
        'model' => $model,
	    'role' => $role
    ]) ?>

</div>
