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

    <p align="right">
        <?= Html::a('删除', ['delete', 'id' => $model->reply_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除么?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ticket_id',
            'd.real_name',
            'content',
            'is_attachment',
            'reply_time:datetime',
            'reply_status',
        ],
    ]) ?>

</div>
