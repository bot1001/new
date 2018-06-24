<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */

$this->title = '创建小区';
$this->params['breadcrumbs'][] = ['label' => '小区', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
