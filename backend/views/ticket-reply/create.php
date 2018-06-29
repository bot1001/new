<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */

$this->title = '添加回复';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Replies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-reply-create">

    <?= $this->render('_form', [
        'model' => $model,
	    'assignee' => $assignee
    ]) ?>

</div>
