<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 16:35
 */

use yii\widgets\ActiveForm;
$model = new \common\models\ProductProperty();
?>
<style>
    #new{
        display: inline-flex;
    }
    #price,#size,#color, #quantity{
        border: solid 1px rgba(128, 128, 128, 0.57);
        border-radius: 3px;
        height: 30px;
    }

    #price, #size, #color{
        margin-right: 2%;
        width: 23%;
    }

    #quantity {
        width: 23%;
    }

    #submit{
        background: rgba(32, 178, 170, 0.51);
    }

    #add_image{
        margin-top: 5px;
        background: rgba(128, 128, 128, 0.05);
        border-radius: 5px;
    }

    /*设置上传图片功能按钮样式*/
    .per_upload_text {
        width: 45px;
        float: left;
        padding-top: 18px;
    }
    .per_real_img {
        position: absolute;
        width: 130px;
        height: 73px;
        top: 1px;
        left: 1px;
        overflow: hidden;
        cursor: pointer;
    }
    .per_upload_con {
        width: 200px;
        height: 74px;
        position: relative;
    }
    .per_upload_img {
        width: 130px;
        height: 73px;
        border: 1px solid #e4e4e4;
        cursor: pointer;
        float: left;
        margin-right: 12px;
        color: #999999;
        line-height: 73px;
        text-align: center;
        border-radius: 5px;
    }
    .form-group {
        margin-bottom: -10px;
    }

    #submit{
        position: relative;
        top: 15px;
        height: 45px;
        width: 100px;
        font-size: 25px;
        color: blue;
        border-radius: 15px;
    }
    sure{
        color: black;
        position: relative;
        top: -3px;
    }

</style>

<script>
    function submit() {
        // 设置变量
        var price = document.getElementById('price');
        var size = document.getElementById('size');
        var color = document.getElementById('color');
        var quantity = document.getElementById('quantity');
        var btn = document.getElementById('btn');

        var price = price.value;
        var size = size.value;
        var color = color.value;
        var quantity = quantity.value;
        var btn = btn.value;
        var id = <?= $id ?>;

        if(price == '' || size == '' || color == '' || quantity == '' || btn == '')
        {
            alert('请确保所有数据不能为空！');
            return ;
        }
        // 设置请求
        var xhr = new XMLHttpRequest();
        xhr.open('get', "/product/add?id="+id+"&price="+price+"&size="+size+"&color="+color+"&image="+btn+"&quantity="+quantity, true);
        xhr.onload = function(){
            var text = this.responseText;
            if(text == '1'){
                alert('操作成功');
                location.reload();
                document.getElementById('add').removeAttribute('style', 'display: none;'); //设置样式
            }else {
                alert('操作失败，请联系管理员');
            }

        }
        xhr.send(); //发送请求
    }
</script>

<div id="new">
    <input type="text" id="price" oninput="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" placeholder="价格"/>
    <input type="text" id="size"  oninput="value=value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"" placeholder="尺寸"/>
    <input type="text" id="color" oninput="this.value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')" placeholder="颜色"/>
    <input type="number" id="quantity" oninput = "value=value.replace(/[^\d]/g,'')" placeholder="库存"/>
</div>

<div id="new">
    <div id="add_image">
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'image')->widget('common\widgets\upload\FileUpload')->label(false) ?>
        <?php ActiveForm::end() ?>
    </div>

    <button id="submit" title="提交" onclick="submit()">
        <span class="glyphicon glyphicon-check" style="padding-top: 5px"><sure>确定</sure></span>
    </button>

</div>
