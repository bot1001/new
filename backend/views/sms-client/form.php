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
        height: 300px;
        max-width: 350px;
        border-radius: 10px;
        background: #ffffff;
        margin-left:5%
    }
</style>

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
            <?= Html::submitButton('确定', ['class' => 'btn btn-success']) ?>
            <?= Html::a('预览', '#', ['class' => 'btn btn-success', 'onclick' => "phone()"]) ?>
        </div>
        <br />
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div id="sms01">
    <div class="col-sm-3" id="view">
        46546
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
                document.getElementById( 'view' ).innerHTML = '<a href= "<?php echo Url::to(['/order/print', 'order_id' => $order_id, 'amount' => $order_amount]); ?>">支付成功！</a>';
            },
            error : function() {
                // document.getElementById( 'submit' ).innerHTML = '<input type="submit" disabled value="确定" class="btn info"></input>';
                // alert("手机号码或姓名验证失败！");
            }
        });
    }
</script>

<!--<script type="text/javascript">-->
<!--    function OnInput (event) {-->
<!--        alert (event.target.value);-->
<!--    }-->
<!--    function OnPropChanged (event) {-->
<!--        if (event.propertyName.toLowerCase () == "value") {-->
<!--            alert (event.srcElement.value);-->
<!--        }-->
<!--    }-->
<!--</script>-->
<!--<form>-->
<!--    用户名:<input name="user" type="text" oninput="OnInput (event)" onpropertychange="OnPropChanged (event)"/>-->
<!--</form>-->






