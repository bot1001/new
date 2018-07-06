<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\models\Menu;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */
AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
        'menus' => Menu::getMenuSource(),
        'routes' => Menu::getSavedRoutes(),
    ]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>

<div class="menu-form" style="max-width: 500px;background:#FFFFFF; border-radius: 15px">
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128, 'placeholder' => '名称'])->label(false) ?>

            <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name', 'placeholder' => '父级菜单'])->label(false) ?>

            <?= $form->field($model, 'route')->textInput(['id' => 'route', 'placeholder' => '路由'])->label(false) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'order')->input('number', ['placeholder' => '排序'])->label(false) ?>

            <?= $form->field($model, 'data')->textarea(['rows' => 4, 'placeholder' => '数据，格式为：{"icon":"plus text-yellow"}'])->label(false) ?>
        </div>
    </div>

    <div class="form-group" align="center">
        <?=
        Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord
                    ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
