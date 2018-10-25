<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */

$this->title = '新建';
$this->params['breadcrumbs'][] = ['label' => 'Phone Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
