<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Recharge */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Recharges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
