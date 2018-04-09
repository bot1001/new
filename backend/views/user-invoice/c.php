<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = '条件筛选';
$this->params['breadcrumbs'][] = ['label' => '生成订单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
	    'id' => $id,
	    'cost' => $cost,
	    'm' => $m,
	    'y' =>$y,
    ]) ?>

</div>
