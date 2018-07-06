<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SysUser;
use app\models\CommunityBasic;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\SysCommunity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-community-form" style="max-width: 500px; background: #ffffff; border-radius:15px;">

   <?php $comm = CommunityBasic::community();//关联小区 	?>
   
    <?php $form = ActiveForm::begin(); ?>
    <div style="width: 90%; margin: auto">
        <?= $form->field($model, 'sys_user_id')->dropDownList($sys); ?>

        <?= $form->field($model, 'community_id')->dropDownList($comm, ['multiple' => true]) ?>

        <div class="form-group" align="center">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
