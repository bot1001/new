<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sms */

$this->title = '模板更新：' . $model->sign_name;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sms-update" style="max-width: 500px; background: #FFFFFF; border-radius:10px;margin: auto">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
