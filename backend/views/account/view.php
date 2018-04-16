<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => '内部用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('设置关联小区', ['workr/create', 'user_id' => $model->user_id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('修改', ['update','id' => $model->user_id],['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'user_id',
//            'account_id',
	        'work.work_number',
            'user_name',
//          'password',
            'mobile_phone',
            //'qq_openid',
            //'weixin_openid',
            //'weibo_openid',
            //'account_role',
            //'new_message',
            'status',
        ],
    ]) ?>

</div>
