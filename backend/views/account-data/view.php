<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserData */

$this->title = $model->account_id;
$this->params['breadcrumbs'][] = ['label' => 'User Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'account_id',
            'real_name',
	        'reg_time:date',
            /*'gender',
            'face_path',
            'province_id',
            'city_id',
            'area_id',
            
            'nickname',
            'property',*/
        ],
    ]) ?>

</div>
