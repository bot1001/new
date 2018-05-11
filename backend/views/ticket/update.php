<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

$this->title = '更新：' . $model->ticket_id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ticket_id, 'url' => ['view', 'id' => $model->ticket_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-basic-update">

    <?= $this->render('_form', [
        'model' => $model,
	    'community' => $community,
		'assignee' => $assignee
    ]) ?>

</div>
