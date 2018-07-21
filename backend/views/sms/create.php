<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sms */

$this->title = '添加模板';
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-create" style="max-width: 500px; background: #FFFFFF; border-radius:10px; margin: auto;">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
