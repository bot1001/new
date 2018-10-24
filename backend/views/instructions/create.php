<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Instructions */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Instructions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
