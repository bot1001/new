<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommunityBuilding */

$this->title = 'New';
$this->params['breadcrumbs'][] = ['label' => '楼宇管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-building-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'company' => $company
    ]) ?>

</div>
