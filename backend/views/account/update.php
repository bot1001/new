<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = '更新: ' . $model->user_name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = '提交';
?>
<div class="user-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
	    'userdata' => $userdata
    ]) ?>

</div>
