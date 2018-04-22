<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TicketReply */

$this->title = 'Create Ticket Reply';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Replies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-reply-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
