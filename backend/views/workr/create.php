<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorkR */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Work Rs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .work-r-create{
        max-width: 550px;
        margin:auto;
        background:#ffffff;
        border-radius: 15px;
    }
    h1{
        text-align: center;
        position: relative;
        top: 15px;
    }
</style>
<div class="work-r-create">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'company' => $company,
            'user' => $user
        ]) ?>
    </div>
</div>
