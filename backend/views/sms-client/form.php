<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\SmsClient */
/* @var $form yii\widgets\ActiveForm */
$this->title = '短信发送';
$this->params['breadcrumbs'][] = ['label' => 'Sms Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .sms-client-form{
        width: 500px;
        background:#ffffff;
        border-radius: 10px;
    }
    #sms{
        /*padding: 5px;*/
        margin-buttom: 3%;
        margin-left: 3%;
        width: 94%;
        height: 94%;
        border-radius:10px;
    }
    #sms01 .col-sm-3{
        min-height: 300px;
        max-width: 350px;
        border-radius: 10px;
        background: #ffffff;
        margin-left:5%
    }
    #title{
        width: 30%;
        border-radius: 10px;
        text-align: center;
        background: #3baae3;
        position: relative;
        margin: auto;
        top: 10px;
        font-size: 20px;
        font-weight: bolder;
    }
    .preview{
        position: relative;
        top: 15px;
        font-size: 16px;
        width: 90%;
        margin:auto;
        background: #0DE842;
        border-radius: 5px;
    }
    g{
        font-weight: bolder;
    }
</style>

<?php
$message = Yii::$app->getSession()->getFlash('result'); //获取提示信息
if($message){
    echo "<script>alert('$message')</script>";
}
?>

<div class="sms-client-form col-sm-6">
    <div id="sms">
        <br />
        <?php $form = ActiveForm::begin(['id' => 'form']); ?>

        <?= $form->field($model, 'community')->dropDownList(\app\models\CommunityBasic::community(), ['prompt'=>'请选择小区', 'maxlength' => true, 'id' => 'community' ])->label(false) ?>

        <?= $form->field($model, 'building')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'options'=>['id'=>'building'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['community'],
                'placeholder'=>'请选择楼宇',
                'url'=>Url::to(['/costrelation/b2'])
            ]
        ])->label('楼宇'); ?>

        <?= $form->field($model, 'number')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'options'=>['id'=>'number'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['building'],
                'placeholder'=>'请选择单元',
                'url'=>Url::to(['/costrelation/number'])
            ]
        ])->label('单元'); ?>

        <?= $form->field($model, 'room')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'options'=>['id'=>'room'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['number'],
                'placeholder'=>'请选择房号',
                'url'=>Url::to(['/costrelation/re']),
                'params'=>['building'], //另一个上级目录ID
            ]
        ])->label('房号'); ?>

        <?= $form->field($model, 'phone')->input('number', ['id' => 'phone']) ?>

        <div class="form-group" style="text-align: center">
            <span id="submit">
                <input type="submit" disabled value="确定" class="btn info">
            </span>

            <?= Html::a('预览', '#', ['class' => 'btn btn-success', 'onclick' => "phone()"]) ?>
        </div>
        <br />
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div id="sms01">
    <div class="col-sm-3" id="view">
        <div id="title">实时预览</div>
        <div class="preview">
            <div style="width: 90%; margin: auto">
                【裕家人】尊敬的业主，您好。您现居住的房子:<g><span id="address"> XXXX小区 XX栋 X单元 XXXX </span></g> 当月费用：<g><span id="now"> XXX.XX </span></g> 元，往期费用：<g><span id="old"> XXX.XX </span></g> 元,合计：<g><span id="amount"> XXX.XX </span></g> 元（仅供参考），
                如有疑问请联系客服员<g><span id="cellphone"> 0772-5314739 </span></g> ;为维护良好信用记录，请您于当月16日前通过裕家人或移步物业服务中心缴清费用，<?= $guest ?>祝您身体健康、工作顺利。
            </div>
        </div>
    </div>
</div>

<script>
    function phone() {
        $.ajax({
            type: "GET",//方法类型
            dataType: "json",//预期服务器返回的数据类型
            url: "/sms-client/message" ,//验证地址
            data: $('#form').serialize(),
            success: function (result) {
                var result = eval(result);
                if(result.end == 1)
                {
                    document.getElementById( 'submit' ).innerHTML = '<input type="submit" value="确定" class="btn info">';
                }

                if(result.end == 0)
                {
                    document.getElementById( 'submit' ).innerHTML = '<input type="submit" disabled value="确定" class="btn info">';
                }
                document.getElementById( 'address' ).innerHTML = result.name;
                document.getElementById( 'now' ).innerHTML = result.now;
                document.getElementById( 'old' ).innerHTML = result.old;
                document.getElementById( 'amount' ).innerHTML = result.amount;
                document.getElementById( 'cellphone' ).innerHTML = result.phone;
            },
            error : function() {
                document.getElementById( 'submit' ).innerHTML = '<input type="submit" disabled value="确定" class="btn info">';
                alert("选择有误，请重新选择！");
            }
        });
    }
</script>





