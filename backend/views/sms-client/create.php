<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SmsClient */

$this->title = 'Create Sms Client';
$this->params['breadcrumbs'][] = ['label' => 'Sms Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
