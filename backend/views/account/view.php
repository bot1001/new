<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = $model->mobile_phone;
$this->params['breadcrumbs'][] = ['label' => 'User Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('首页', ['account/index'],['class' => 'btn btn-info']) ?>
        <?= Html::a('设置关联房屋', ['relationship/create', 'account_id' => $model->account_id,'mobile_phone' => $model->mobile_phone], ['class' => 'btn btn-info']) ?>
        <?= Html::a('修改', ['update','id' => $model->user_id],['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'account_id',
            //'user_name',
            'password',
            'mobile_phone',
            //'qq_openid',
            //'weixin_openid',
            //'weibo_openid',
            //'account_role',
            //'new_message',
            //'status',
        ],
    ]) ?>

</div>
