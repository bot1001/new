<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = '缴费管理';
$this->params['breadcrumbs'][] = ['label' => 'User Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
