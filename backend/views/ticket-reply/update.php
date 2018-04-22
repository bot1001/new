<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */

$this->title = 'Update Ticket Reply: ' . $model->reply_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Replies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reply_id, 'url' => ['view', 'id' => $model->reply_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-reply-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
