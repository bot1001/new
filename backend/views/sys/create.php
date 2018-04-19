<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */

$this->title = '创建绑定小区';
$this->params['breadcrumbs'][] = ['label' => 'Sys Communities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-community-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'id' => $id
    ]) ?>

</div>
