<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => '内部用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'a' => $a,
	    'userdata' => $userdata
    ]) ?>

</div>
