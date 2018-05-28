<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Advertising */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertising-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
