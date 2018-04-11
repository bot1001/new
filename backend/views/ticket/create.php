<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-create">

    <?= $this->render('_form', [
        'model' => $model,
	    'community' => $community,
	    'assignee' => $assignee
    ]) ?>

</div>
