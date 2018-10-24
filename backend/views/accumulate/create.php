<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StoreAccumulate */

$this->title = 'Create Store Accumulate';
$this->params['breadcrumbs'][] = ['label' => 'Store Accumulates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-accumulate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
