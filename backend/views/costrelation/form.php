<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CostRelation;
use app\models\CommunityBasic;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CostRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .cost{
        width:550px;
        background-color:#F3F3F3;
        border-radius:20px;
        margin: auto;
    }

    #relation{
        width: 90%;
        margin: auto;
    }
</style>
<script>
    $(document).ready(function () {
        $("#span").click(function () {
            $("add").append(
                "<input><br />"
            );
        });
    });
</script>
<div class="cost">

    <div id="relation">
        <?php $form = ActiveForm::begin(/*['id' => 'form-id',
									 'enableAjaxValidation' => true,
									]*/); ?>

        <br>

        <div class="row" style="display: none">
                <?= $form->field($model, 'community')->dropDownList($community);?>
                <?= $form->field($model, 'building_id')->dropDownList($building) ?>
                <?= $form->field($model, 'realestate_id')->dropDownList($num) ?>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <?= $form->field($model, 'price')->dropDownList($cost,['prompt'=>'请选择费项','id'=>'costName'])->label(false) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'cost_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'costrelation-price'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['costName'],
                        'placeholder'=>'请选择单价','multiple'=>'multiple',
                        'url'=>Url::to(['/costrelation/p'])
                    ]
                ])->label(false) ?>
            </div>
            <button type="button" id="span"><span class=" add glyphicon glyphicon-plus"></span></button>
        </div>
        <div>
            <add></add>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'from', [
                    'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                    'options'=>['class'=>'drp-container']])
                    ->widget(DateRangePicker::classname(), [
                        'useWithAddon'=>true,
                        'pluginOptions'=>[
                            'singleDatePicker'=>true,
                            'showDropdowns'=>true,
                            'useWithAddon'=>true,
                        ]
                    ])->textInput(['value' => date('Y-m-d')]) ?>
            </div>

            <div class="col-lg-4">
                <?= $form->field($model, 'status')->dropDownList(['禁用', '启用'],['prompt'=>'请选择']) ?>
            </div>
        </div>
        <div class="col-lg-12">
            <?= $form->field($model, 'property')->textArea(['maxlength' => true]) ?>
        </div>

        <div class="form-group" align="center">
            <?= Html::submitButton($model->isNewRecord ? '保存' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?php if(!empty($relation)){ ?>
    </div>

    <p></p>
    <p>此房屋当前绑定的 <l>费项</l> 如下：</p>
    <table border="1" width="60%">
    	<tr>
    		<th>序号</th>
    		<th>名称</th>
    		<th>备注</th>
    	</tr>
    	<?php foreach($relation as $key => $r): $r = (object)$r ?>
    	<tr>
    		<td align="center"><?= $key+1 ?></td>
    		<td><?= $r->cost_name; ?></td>
    		<td align="center"><?= $r->property; ?></td>
    	</tr>
    	<?php endforeach; ?>
    </table>
   <?php } ?>
    <br>
</div>
