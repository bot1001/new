<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/10/20
 * Time: 23:43
 */
use yii\helpers\Url;

$taxonomy = \common\models\StoreTaxonomy::brand($type = 0);
?>

<style>
    .code{
        margin-top: 20px;
    }
</style>

<script>
    var company_type = document.getElementById('company_type');
    var company_code = document.getElementById('company_code');
    var company_name = document.getElementById('company_name');
    var company_address = document.getElementById('company_address');
    var company_tax = document.getElementById('company_tax');
    var company_count = document.getElementById('company_count');
    var company_person = document.getElementById('company_person');
    var company_qr = document.getElementById('company_qr');

    //设置监视器
    company_type.addEventListener("change", c_type);
    company_code.addEventListener("change", c_code);
    company_name.addEventListener("change", c_n);
    company_address.addEventListener("change", c_a);
    company_tax.addEventListener("change", c_tax);
    company_count.addEventListener("change", c_count);
    company_person.addEventListener("change", c_p);
    company_qr.addEventListener("change", c_qr);

    function c_type() { //公司类型判断
        C_type = company_type.value;
        if(C_type != ''){
            document.getElementById('co_type').innerHTML = '';
        }else{
            document.getElementById('co_type').innerHTML = '公司地址不能为空';
        }
    }

    function c_code() { //代码判断
        C_code = company_code.value; //输入的值
        if(C_code != '')
        {
            document.getElementById('co_code').innerHTML = '';
        }else {
            document.getElementById('co_code').innerHTML = '公司代码不能为空';
        }
    }

    function c_n() { //公司名称判断
        C_name = company_name.value;
        if(C_name != ''){
            document.getElementById('co_name').innerHTML = '';
        }else{
            document.getElementById('co_name').innerHTML = '公司名称不能为空';
        }
    }

    function c_a() { //公司地址判断
        C_address = company_address.value;
        if(C_address != ''){
            document.getElementById('co_address').innerHTML = '';
        }else{
            document.getElementById('co_address').innerHTML = '公司地址不能为空';
        }
    }

    function c_tax() { //公司行业判断
        C_tax = company_tax.value;
        if(C_tax != ''){
            document.getElementById('co_tax').innerHTML = '';
        }else{
            document.getElementById('co_tax').innerHTML = '公司行业所属不能为空';
        }
    }

    function c_count() { //公司人数判断
        C_count = company_count.value;
        if(C_count > 0){
            document.getElementById('co_count').innerHTML = '';
        }else{
            C_count = '';
            document.getElementById('co_count').innerHTML = '公司人数有误';
        }
    }

    function c_p() { //公司联系人判断
        C_person = company_person.value;
        if(C_person != ''){
            document.getElementById('co_person').innerHTML = '';
        }else{
            document.getElementById('co_person').innerHTML = '公司联系人不能为空';
        }
    }

    function c_qr() { //公司邀请码判断
        C_qr = company_qr.value;
    }

    function next_3() {
        if (Phone == '' || Name == '' || P == '' || C_type == '' || C_code == '' || C_name == '' || C_address == '' || C_tax == '' || C_count < 1 || C_person == '')
        {
            alert('您输入的注册信息有误，请检查！');
        }else{
            var xhr = new XMLHttpRequest(); //实例化请求
            xhr.open('GET', '/store/r?phone='+Phone+'&Name='+Name+'&password='+P+'&type='+C_type+'&code='+C_code+'&name='+C_name+'&address='+C_address+'&tax='+C_tax+'&count='+C_count+'&person='+C_person+'&qr='+C_qr, true); //设置请求连接
            xhr.onload = function(){
                var text = this.responseText;
                if(text == '1'){
                    alert('注册成功！');
                    $("#M").load("/store/password", {"name" : "end"});
                }else{
                    alert('注册失败，请检查对应的信息');
                }
            }
            xhr.send();
        }
    }
</script>

<div class="step">
    <img src="<?= Url::toRoute('image/step.png') ?>" id="step">
</div>

<div style="min-height: 1000px">
    <div id="one">
        <div class="phone">

            <div class="mobile_phone">
                <div class="china">公司名称</div>
                <div class="input">
                    <input id="company_name" type="text" name="password01" placeholder="请输入单位全称" class="in"/>
                </div>
            </div>
            <div id="co_name" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">注册类型</div>
                <div class="input">
                    <select class="in" id="company_type">
                        <option value="">请选择</option>
                        <option value="1">公司</option>
                        <option value="2">个人</option>
                    </select>
                </div>
            </div>
            <div id="co_type" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">营业执照</div>
                <div class="input">
                    <input id="company_code" type="text" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" placeholder="不能有中文" class="in"/>
                </div>
            </div>
            <div id="co_code" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">公司地址</div>
                <div class="input">
                    <input id="company_address" type="text" name="password01" placeholder="详细公司地址" class="in"/>
                </div>
            </div>
            <div id="co_address" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">联系人</div>
                <div class="input">
                    <input id="company_person" type="text" maxlength="16" placeholder="请输入单位负责人" class="in"/>
                </div>
            </div>
            <div id="co_person" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">所属行业</div>
                <div class="input">
                    <select class="in" id="company_tax">
                        <option value="">请选择</option>
                        <?php foreach ($taxonomy as $t): $t = (object)$t ?>
                            <option value="<?= $t->id ?>"><?= $t->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div id="co_tax" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">公司人数</div>
                <div class="input">
                    <input id="company_count" type="number" onkeyup="this.value=this.value.replace(/[^0-9-]+/)" minlength="6" placeholder="请输入现有职员人数" class="in"/>
                </div>
            </div>
            <div id="co_count" class="com"></div>

            <div class="mobile_phone code">
                <div class="china">邀请码</div>
                <div class="input">
                    <input id="company_qr" type="text" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" placeholder="不能有中文（可留空）" minlength="6" placeholder="" class="in"/>
                </div>
            </div>
            <div id="co_qr" class="com"></div>
        </div>

        <div class="phone code">
            <div class="m" id="m">
                <button class="btn-block" style="background: rgba(221, 0, 0, 0.53)" onclick="next_3()">下一步</button>
            </div>
        </div>
    </div>
</div>

