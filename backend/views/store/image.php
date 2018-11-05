<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 16:35
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<style>
    .image_change{
        margin: auto;
        text-align: center;
        min-height: 150px;
        max-width: 300px;
        border: solid 1px rgba(128, 128, 128, 0.81);
        border-radius: 8px;
        box-shadow: 5px 5px 5px #d0e1bc;
    }
    .preview{
        margin: auto;
        height: 80px;
        background: rgba(182, 182, 182, 0.14);
        top: 5px;
        font-size: 20px;
        line-height: 70px;
    }

    #view, #i_file, .i_input, .preview{
        width: 140px;
        border-radius: 5px;
        position: relative;
    }

    #view{
        height: 80px;
        border: solid 1px rgba(128, 128, 128, 0.54);
    }
    .i_input{
        width: 180px;
        height: 30px;
        top: 10px;
        display: flex;
        margin: auto;
    }
    #i_file{
        background: #bfbfbf;
    }

    #i_submit{
        width: 50px;
        border-radius: 5px;
    }
</style>

<script>
    function setImage() {//下面用于图片上传预览功能
        var img=document.getElementById("i_file");

        var img_p=document.getElementById("view");

        if(img.files &&img.files[0])
        {
            //火狐下，直接设img属性
            img_p.style.display = 'block';
            img_p.style.width = '140px';
            img_p.style.height = '80px';

            var data = window.URL.createObjectURL(img.files[0]); //临时文件路劲
            img_p.src = data;
            //img_p.src = img.files[0].getAsDataURL();//火狐7以上版本不再支持getAsDataURL()方式
        }
        return true;
    }
</script>
<div class="image_change">
    <?php $form = ActiveForm::begin( ); ?>

    <div class="preview">
        <?= Html::img($image, ['alt' => '图片预览', 'id' => 'view']) ?>
    </div>

    <div class="i_input form-group field-uploadform-file">
        <div class="">
            <?= $form->field($model, 'file')->fileInput(['id' => 'i_file', 'onchange' => "setImage()"])->label(false) ?>
        </div>

        <div class="">
            <button type="submit" id="i_submit"> 确定 </button>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>