<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StoreTaxonomy */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => 'Store Taxonomies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-taxonomy-create">

<!--    <h1>--><?php // Html::encode($this->title) ?><!--</h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type
    ]) ?>

</div>
