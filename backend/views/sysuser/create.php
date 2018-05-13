<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SysUser */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Sys Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-user-create">

    <?= $this->render('_form', [
        'model' => $model,
	    'company' => $company,
		'role' => $role
    ]) ?>

</div>
