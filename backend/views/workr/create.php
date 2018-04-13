<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorkR */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Work Rs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-r-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'company' => $company,
	    'user' => $user
    ]) ?>

</div>
