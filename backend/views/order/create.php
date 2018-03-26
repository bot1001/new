<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OrderBasic */

$this->title = 'New';
$this->params['breadcrumbs'][] = ['label' => 'Order Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-basic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
