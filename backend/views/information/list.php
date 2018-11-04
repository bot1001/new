<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/11/4
 * Time: 12:14
 */

use mdm\admin\components\Helper;

$this->title = '系统消息';
?>

<style>
    /*引入页面样式文件*/
    <?= $this->render("../../web/css/information.css"); ?>
</style>

<div class="row">
    <?php if(Helper::checkRoute('user-invoice/index')){ ?>
        <div class="box col-lg-4 box1">
            <?= $this->render('box1', [ 'dataProvider' => $wuye]) ?>
        </div>
    <?php } ?>

    <?php if(Helper::checkRoute('product/index')){ ?>
        <div class="box col-lg-4 box2">
            <?= $this->render('box2', ['dataProvider' => $dataProvider]) ?>
        </div>
    <?php } ?>

</div>
