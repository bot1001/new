<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */

$this->title = $model->reply_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Replies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-reply-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->reply_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->reply_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'reply_id',
            'ticket_id',
            'account_id',
            'content:ntext',
            'is_attachment',
            'reply_time:datetime',
            'reply_status',
        ],
    ]) ?>

</div>
