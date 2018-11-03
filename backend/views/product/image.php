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
    .i_view{
        margin: auto;
        height: 80px;
        background: rgba(182, 182, 182, 0.14);
        top: 5px;
        font-size: 20px;
        line-height: 70px;
    }

    #view, #i_file, .i_input, .i_view{
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
         var f = document.getElementById('i_file');

        // f.addEventListener("change", submit);
         var data = new FormData();
         data.append('upload_file'+i, file);

         var data = new FormData();
         data.append("myfile", document.getElementById("i_file").files[0]);

        function submit() {
            $.ajax({
                url:'/auto/image',
                type:'POST',  /*提交方式*/
                data:data,
                cache: false,
                contentType: false,        /*不可缺*/
                processData: false,         /*不可缺*/
                success:function(result){
                    if(result == '')
                    {
                        alert('上传成功');
                    }else{
                        alert('上传失败');
                    }
                },
                error:function(){
                    alert('上传出错');
                }
            });
    }
</script>
<div class="image_change">
    <?php $form = ActiveForm::begin(  ); ?>

    <div class="i_view">
        <?= Html::img($image, ['alt' => '图片预览', 'id' => 'view']) ?>
    </div>

    <div class="i_input">
        <div class="">
            <?= $form->field($model, 'file')->fileInput(['id' => 'i_file'])->label(false) ?>
        </div>

        <div class="">
            <button type="submit" id="i_submit"> 确定 </button>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>

