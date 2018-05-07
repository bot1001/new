<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SysUser */

$this->title = '更新' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sys Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sys-user-update">

    <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
