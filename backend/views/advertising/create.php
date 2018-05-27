<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Advertising */

$this->title = 'Create Advertising';
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertising-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
