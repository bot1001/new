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
    .second{
        text-align: center;
        padding: 13px;
        font-size: 16px;
        color: gray;
    }
</style>

<script>
    //设置公共变量
    var Code = '';  //验证码
    var Phone = '';  //用户手机号码
    var Name = '';  //用户登录名
    var P = '';  //用户密码
    var C_type = '';  //商城类型，公司或私人
    var C_code = '';  //商城（公司)代码
    var C_name = '';  //商城名称
    var C_address = '';  //商城地址
    var C_tax = '';  //商城行业
    var C_count = '';  //商人职员人数
    var C_person = '';  //商城联系人
    var C_qr = '';  //商户开通邀请码
    var num = 61; //验证码计时器
    var n_code = ''; //服务器发送的验证码
    var n_time = '';

    function search() { //查询账号是否已存在
        var phone = document.getElementById('phone');
        var phone01 = phone.value;

        if(phone01.length == 11)
        {
            var xhr = new XMLHttpRequest();
            xhr.open('GET','/store/find?phone='+phone.value, true);
            xhr.onload = function(){
                var text = this.responseText;
                if(text == ''){
                    document.getElementById('p_phone').innerHTML = '号码不可用';
                }else{
                    document.getElementById('p_phone').innerHTML = '号码可用';
                }
            }
            xhr.send();
        }else{
            alert('手机号码有误，请检查');
        }
    }

    function down(){ //时间自动减去1
        num --;
        document.getElementById('change').innerHTML = '<div  id="getcode" class="second">'+num+'s后重新获取</div>';
            console.log(num)
        if(num == 0)
        {
            clearInterval(Interval); //清理定时器
            num = 61;
            document.getElementById('change').innerHTML =  '<button id="getcode" onclick="code()">重新获取</button>';
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
            var xhr = new XMLHttpRequest(); //实例化请求
            xhr.open('GET', '/sms/send?time='+n_time+'&phone='+Phone, true); //设置请求连接
            xhr.onload = function(){
                var text = JSON.parse(this.responseText);
                if(text == '2'){
                   document.getElementById('p_phone').innerHTML = '验证码获取失败，请1分钟后再试';
                }else{
                    Code = text.code;//将获取的验证码赋值给公共变量
                    n_time = text.timeStamp;//用户设备时间
                    document.getElementById('p_phone').innerHTML = '';
                    document.getElementById('m').innerHTML = '<button class="btn-block" style="background: rgba(221, 0, 0, 0.53)" onclick="next()">下一步</button>';
                }
            }
            xhr.send();


            //定时更新验证码发送后的时间
            Interval = setInterval(function () {
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
            clearInterval(Interval); //清理定时器
            $("#M").load("/store/password", {"name" : "reg_password"}); //加载用户名及密码页面
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

            <div class="com" id="p_phone"></div>

            <div class="mobile_phone code">
                <div><input id="code" class="in" value=""></div>
                <div id="change"><button id="getcode" onclick="code()">获取验证码</button></div>
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
