<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = '预交';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-create">

    <?= $this->render('_form', [
        'model' => $model,
	    'cost' => $cost
    ]) ?>

</div>
