<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserRelationshipRealestate */

$this->title = 'New: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '关联房号', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-relationship-realestate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
