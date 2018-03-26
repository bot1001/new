<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = 'Update User Account: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
