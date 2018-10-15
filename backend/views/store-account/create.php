<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StoreAccount */

$this->title = 'Create Store Account';
$this->params['breadcrumbs'][] = ['label' => 'Store Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
