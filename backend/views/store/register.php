<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/20
 * Time: 8:39
 */

use yii\helpers\Url;

$this->title = '商户注册';

?>

<style>
    .main{
        width: 100%;
        min-height: 800px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.63);
        position: relative;
        top: 10px;
    }

    /*宽度设置*/
    .step, .phone, .m{
        width: 70%;
    }
    /*设置边距*/
    .step, .phone, .service, #one, .mobile_phone{
        margin: auto;
    }

    #one{
         width: 600px;
         /*background: rgba(0, 0, 0, 0.14);*/
     }
    .step{
        height: 100px;
        position: relative;
        top: 100px;
    }
    #step{
        width: 100%;
        height: 100px;
    }
    .phone{
        height: 250px;
        position: relative;
        top: 100px;
        text-align: center;
    }
    .mobile_phone, .m{
        height: 65px;
        min-width: 450px;
        background: rgba(255, 255, 255, 0.4);
        display: inline-flex;
        position: relative;
        top: 40px;
        border-radius: 5px;
        border: solid 1px rgba(128, 128, 128, 0.38);
        font-size: 23px;
    }
    .china{
        width: 40%;
        padding: 15px;
        border-right: solid 1px rgba(128, 128, 128, 0.38);
    }
    .input{
        background: #000;
        background: rgba(255, 255, 255, 0.4);
    }

    .in{
        border: solid 1px rgba(0, 0, 0, 0.1);
        height: 50px;
        margin-top: 6px;
        margin-left: 10px;
        border-radius: 3px;
        background: rgba(255, 255, 255, 0.4);
    }

    .glyphicon-search{
        font-size: 30px;
        color: rgba(144, 144, 0, 0.48);
        margin-top: 18px;
        margin-left: 8px;
        border: solid 0px;
        background: rgba(0, 0, 0, 0);
    }
    #getcode{
        width: 170px;
        height: 51px;
        margin-left: 10px;
    }

    .code{
        margin-top: 40px;
    }
    #getcode{
        margin-top: 5px;
    }
    .btn-block{
        background: rgba(221, 0, 0, 0.53);
    }
    .service{
        position: relative;
        top: 50px;
        font-size: 20px;
        text-align: right;
        width: 450px;
    }

</style>

<script>
    var Code = '';
    var Phone = '';
    function search() { //查询账号是否已存在
        var phone = document.getElementById('phone');
        alert('查询功能有待开发');
    }
    function code() {//获取验证码
        var phone = document.getElementById('phone');
        Phone = phone.value;

        //接下来执行获取短信验证码程序
        if(Phone.length != 11){
            document.getElementById('m').innerHTML = '<button class="btn-block" disabled style="float: right" onclick="next()">下一步</button>';
            alert('输入的手机号码有误！')
        }else {
            Code = '123456'; //修改验证码
            alert('您将要输入的验证码是：'+Code);
            document.getElementById('m').innerHTML = '<button class="btn-block" style="float: right" onclick="next()">下一步</button>';
        }
    }
    function next() { //下一步
        var code = document.getElementById('code');
        var checkbox = document.getElementById('box');
        var check = checkbox.checked;

        if(code.value == '' || Phone == ''){
            alert('验证码或手机号码不能为空！');
        }else if(code.value != Code ){
            alert('您输入的验证码不正确！');
        }else if(!check){
            alert('请仔细阅读《裕家人服务条款》');
        }else{
            alert(Phone);
        }
    }
</script>
<div class="main">
    <div class="step">
        <img src="<?= Url::toRoute('image/step.png') ?>" id="step">
    </div>

    <div id="one">
        <div class="phone">
            <div class="mobile_phone">
                <div class="china">中国 +86</div>
                <div class="input">
                    <input id="phone" type="text" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');" maxlength="11" name="phone"placeholder="请输入手机号码" class="in"/>
                </div>
                <div onclick="search()">
                    <button class="glyphicon glyphicon-search" title="验证是否已注册"></button>
                </div>
            </div>

            <div class="mobile_phone code">
                <div><input id="code" class="in"></div>
                <div><button id="getcode" onclick="code()"> </div>
            </div>
        </div>

        <div class="phone">
            <div class="m" id="m">
                <button class="btn-block" disabled style="float: right" onclick="next()">下一步</button>
            </div>
            <div class="service">
                <input type="checkbox" class="box" id="box" checked="checked"><a href="#">服务条款</a>
            </div>
        </div>
    </div>
</div>
