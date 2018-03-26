<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = 'Update User Invoice: ' . $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'User Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoice_id, 'url' => ['view', 'id' => $model->invoice_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
