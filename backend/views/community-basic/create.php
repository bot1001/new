<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */

$this->title = 'Create Community Basic';
$this->params['breadcrumbs'][] = ['label' => 'Community Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
