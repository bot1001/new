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
        height: auto;
        position: relative;
        top: 100px;
        text-align:justify;
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
        color: gray;
        text-justify:distribute-all-lines;/*ie6-8*/
        text-align-last:justify;/* ie9*/
        -moz-text-align-last:justify;/*ff*/
        -webkit-text-align-last:justify;/*chrome 20+*/
    }
    .input{
        background: #000;
        background: rgba(255, 255, 255, 0.4);
    }

    .in{
        border: solid 1px rgba(0, 0, 0, 0.0);
        height: 50px;
        width: 250px;
        margin-top: 6px;
        margin-left: 10px;
        border-radius: 3px;
        background: rgba(255, 255, 255, 0.4);
        font-size: 16px;
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
        background: rgba(128, 128, 128, 0.58);
    }
    .service{
        position: relative;
        top: 50px;
        font-size: 20px;
        text-align: right;
        width: 450px;
    }
    .com{
        width: 100%;
        color: red;
        position: relative;
        text-align: center;
        top: 40px;
        margin: auto;
    }
</style>

<script>
    //设置公共变量
    var Code = '';  //验证码
    var Phone = '';  //用户手机号码
    var Name = '';  //
    var P = '';  //用户密码
    var C_type = '';  //商城类型，公司或私人
    var C_code = '';  //商城（公司代码
    var C_name = '';  //商城名称
    var C_address = '';  //商城地址
    var C_tax = '';  //商城行业
    var C_count = '';  //商人职员人数
    var C_person = '';  //商城联系人
    var C_qr = '';  //商户开通邀请码
    var num = 61; //验证码计时器

    function search() { //查询账号是否已存在
        var phone = document.getElementById('phone');
        alert('查询功能有待开发');
    }

    function down(){ //时间自动减去1
        num --;
            console.log(num)
        if(num == 0)
        {
            clearInterval(s);
        }
    }


    function code() {//获取验证码
        var phone = document.getElementById('phone');
        Phone = phone.value;

        //接下来执行获取短信验证码程序
        if(Phone.length != 11){
            document.getElementById('m').innerHTML = '<button class="btn-block" disabled onclick="next()">下一步</button>';
            alert('输入的手机号码有误！')
        }else {
            down();
            Code = '123456'; //修改验证码
            alert('您将要输入的验证码是：'+Code);
            document.getElementById('m').innerHTML = '<button class="btn-block" style="background: rgba(221, 0, 0, 0.53)" onclick="next()">下一步</button>';

            //定时更新验证码发送后的时间
            var s = setInterval(function () {
                down();
            }, 1000)
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
            $("#M").load("/store/password", {"name" : "reg_password"});
        }
    }
</script>
<div class="main" id="M">
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
                <div><input id="code" class="in" value=""></div>
                <div><button id="getcode" onclick="code()"> </div>
            </div>
        </div>

        <div class="phone code">
            <div class="m" id="m">
                <button class="btn-block" disabled onclick="next()">下一步</button>
            </div>
            <div class="service">
                <input type="checkbox" class="box" id="box" checked="checked"><a href="#">服务条款</a>
            </div>
        </div>
    </div>
</div>
