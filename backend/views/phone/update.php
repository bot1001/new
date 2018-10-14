<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */

$this->title = 'Update Phone List: ' . $model->phone_id;
$this->params['breadcrumbs'][] = ['label' => 'Phone Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->phone_id, 'url' => ['view', 'id' => $model->phone_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="phone-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
