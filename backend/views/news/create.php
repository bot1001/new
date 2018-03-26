<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommunityNews */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '公告栏列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-news-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
