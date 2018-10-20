<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<style>
    body{
        background: url(<?= Url::toRoute('image/bg.png') ?>);
        background-size: cover;
        height: 110px;
        min-width: 500px;
    }
    .footer{
        position:fixed;
        margin:auto;
        left:0;
        right:0;
        bottom:0;
    }
    .top{
        height: 100px;
        background: rgba(0, 0, 0, 0.08);
    }
    #top{
        display: inline-flex;
        width: 100%;
    }
    .logo, .ready{
        width: 50%;
        margin-top: 10px;
        font-size: 25px;
        color: grey;
    }
    #logo{
        height: 80px;
    }
    .ready{
        text-align: right;
    }
    .span{
        margin-top: 30px;
    }

    gray{
        display: block;
        margin-top: -50px;
        margin-left: 80px;
        color: white;
        font-size: 35px;
    }

    .ready a{
        color: rgba(255, 0, 0, 0.76);
    }
    .ready a:hover{
        color: #ececec;
    }
</style>

<body>
<?php $this->beginBody() ?>

<div class="top">
    <div class="container">
        <div id="top">
            <div class="logo">
                <a href="https://www.gxydwy.com"><img src="<?= Url::toRoute('/image/logo01.png') ?>" id="logo"></a>
                <gray>商户注册</gray>
            </div>

            <div class="ready">
                <div class="span">
                    已有账号？<a href="/login">请登录></a>
                </div>
            </div>
        </div>
    </div>
</div>

   <div class="container">
       <?= Breadcrumbs::widget([
               'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
           ]) ?>

       <?= $content ?>
   </div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') ?>裕家人</p>

        <p class="pull-right">技术支持<?= Html::a(' 裕达物业', 'https://www.gxydwy.com') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
