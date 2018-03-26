<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserRelationshipRealestate */

$this->title = '创建关联房号';
$this->params['breadcrumbs'][] = ['label' => '关联房号', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-relationship-realestate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
