<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TicketBasic */

$this->title = 'Create Ticket Basic';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-basic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
