<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

$this->title = 'Update Ticket Basic: ' . $model->ticket_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ticket_id, 'url' => ['view', 'id' => $model->ticket_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-basic-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
