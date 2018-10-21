<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/10/21
 * Time: 21:05
 */

use yii\helpers\Url;
use yii\helpers\Html;

?>
<script>
    function ref() {
        $("#M").load("/store/password", {"name" : "end"});
    }
</script>

<style>
    .re{
        position: fixed;
        top: 80%;
        left: 50%;
        margin: 0 0 0 0;
        width: 150px;
        height: 200px;
    }
    #re{
        width: 150px;
        height: 100px;
    }

    .going{
        width: 300px;
        height: 100px;
        border-radius: 10px;
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -175px 0 0 -175px;
        background: rgba(13, 232, 66, 0.48);
        font-size: 28px;
        padding-top: 30px;
        text-align: center;
        text-decoration: underline;
    }

</style>
<div class="going">
    <?= Html::a('注册成功,请点击登录', '/login') ?>
</div>

<div class="re">
    <button onclick="ref()" id="re" class="btn-block">点击异步刷新</button>
</div>
