<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

//$this->title = ceshi;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-view">

   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ticket_id',
            'ticket_number',
            'account_id',
            'community_id',
            'realestate_id',
            'tickets_taxonomy',
            'explain1',
            'create_time:datetime',
            'contact_person',
            'contact_phone',
            'is_attachment',
            'assignee_id',
            'reply_total',
           // 'ticket_status',
        ],
    ]);
	
	?>

</div>
