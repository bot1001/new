<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelation */

$this->title = 'New';
$this->params['breadcrumbs'][] = ['label' => '费项关联', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-relation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
