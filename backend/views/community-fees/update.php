<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CommunityFees */

$this->title = 'Update Community Fees: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Community Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="community-fees-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
