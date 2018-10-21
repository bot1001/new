<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/10/20
 * Time: 23:42
 */
use  yii\helpers\Url;
?>

<style>
    .code{
        margin-top: 20px;
    }

</style>

    <div class="step">
        <img src="<?= Url::toRoute('image/step.png') ?>" id="step">
    </div>

<div id="one">
    <div class="phone">
        <div class="mobile_phone">
            <div class="china">用户名</div>
            <div class="input">
                <input id="account" type="text" minlength="2" maxlength="16" placeholder="您的登陆名或账户名" class="in"/>
            </div>
        </div>

        <div id="a_name" class="com"></div>

        <div class="mobile_phone code">
            <div class="china">设置密码</div>
            <div class="input">
                <input id="password01" type="password" minlength="6" name="password01" placeholder="建议使用两种以上的字符组合" class="in"/>
            </div>
        </div>

        <div id="pa01" class="com"></div>

        <div class="mobile_phone code">
            <div class="china">确认密码</div>
            <div class="input">
                <input id="password02" type="password" minlength="6" name="password02" placeholder="请再次输入密码" class="in"/>
            </div>
        </div>
        <div id="pa02" class="com"></div>
    </div>

    <div class="phone code">
        <div class="m" id="m">
            <button class="btn-block"  style="float: right" onclick="next_2()">下一步</button>
        </div>
    </div>
</div>
<script>
    var account_name = document.getElementById('account');
    var password01 = document.getElementById('password01');
    var password02 = document.getElementById('password02');

    //监听器开始
    password01.addEventListener("change", p01);
    password02.addEventListener("change", p02);
    account_name.addEventListener("change", account);

    function account() {
        var N = account_name.value;
        Name = N; //赋值到全局变量
        if(N.length < 2 || N.length > 16){
            document.getElementById('a_name').innerHTML = '用户名长度应在2到16位数区间';
        }else{
            document.getElementById('a_name').innerHTML  = '';
        }
    }

    function p01() {
        var p1 = password01.value;
        var p2 = password02.value;

        if(p1 != ''){ //如果密码长度小于6
            if(p1.length < 6) {
                document.getElementById('pa01').innerHTML = '密码长度应大于6位数';
            }else{
                document.getElementById('pa01').innerHTML = '';
            }
        }

        if (p2 != ''){ //如果两次密码输入的不一致
            if(p1 != p2){
                document.getElementById('pa01').innerHTML = '两次输入密码不一致';
                refuse()
            }else{
                P = p1;
                re();
            }
        }
    }

    function p02() {
        var p1 = password01.value;
        var p2 = password02.value;

        if (p1 != ''){
            if(p1 != p2){
                document.getElementById('pa02').innerHTML = '两次输入密码不一致';
                refuse();
            }else{
                P = p2;
                re();
            }
        }
    }

    function refuse() { //修改下一步按钮
        document.getElementById('m').innerHTML = '<button class="btn-block" disabled onclick="">下一步</button>';
    }

    function re() { //清除提醒
        document.getElementById('pa01').innerHTML = '';
        document.getElementById('pa02').innerHTML = '';
        document.getElementById('m').innerHTML = '<button class="btn-block" style="background: rgba(221, 0, 0, 0.53)" onclick="next_2()">下一步</button>';
    }

    function next_2() {
        var n = account_name.value;
        if(n != ''){
            Name = n;
            $("#M").load("/store/password", {"name" : "reg_company"});
        }else{
            alert('用户名有误');
        }
    }

</script>

