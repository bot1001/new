<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StoreTaxonomy */

$this->title = 'Update Store Taxonomy: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Store Taxonomies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-taxonomy-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
