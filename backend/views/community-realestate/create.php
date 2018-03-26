<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommunityRealestate */

$this->title = 'New';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-realestate-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
