<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SysUser */

$this->title = 'Create Sys User';
$this->params['breadcrumbs'][] = ['label' => 'Sys Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
