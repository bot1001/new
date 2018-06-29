<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */

$this->title = '更新: ' . $model->community_name;
$this->params['breadcrumbs'][] = ['label' => 'Community Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->community_id, 'url' => ['view', 'id' => $model->community_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="community-basic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
