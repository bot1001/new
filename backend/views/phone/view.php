<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PhoneList */

$this->title = $model->phone_name;
$this->params['breadcrumbs'][] = ['label' => 'Phone Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-list-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'phone_id',
            'phone_name',
            'phone_number',
            'phone.phone_name',
//            'have_lower',
            'phone_sort',
        ],
    ]) ?>

    <p align="center">
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->phone_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->phone_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
