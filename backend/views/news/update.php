<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityNews */

$this->title = 'Update Community News: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '小区公告', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view1', 'id' => $model->news_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="community-news-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
