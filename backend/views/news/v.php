<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityNews */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '小区公告', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-news-view" >

    <?= DetailView::widget([
	'template' => '<tr><th>{label}</th><td>{value}</td></tr>', 
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'model' => $model,
        'attributes' => ['content:ntext'],
    ]) ?>
    <p align="center">
        <?= Html::a('更新', ['update', 'id' => $model->news_id], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
