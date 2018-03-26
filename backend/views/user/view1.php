<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
	print_r($id)?>

</div>
