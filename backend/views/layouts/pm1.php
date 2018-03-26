<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Html::encode($this->title) ?></title>
	<?=Html::cssFile('//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css');?>
    <?=Html::cssFile('//cdn.bootcss.com/flat-ui/2.2.2/css/flat-ui.min.css');?>
	<style>
		.navbar{ border-radius:0px;}
	</style>
</head>
<body>
<?php $this->beginBody() ?>
	<?=Html::jsFile("http://libs.baidu.com/jquery/1.11.1/jquery.min.js");?>
    <?=Html::jsFile('//cdn.bootcss.com/flat-ui/2.2.2/js/flat-ui.min.js');?>
    
	<nav class="navbar navbar-inverse" role="navigation" id="nav">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
		<span class="sr-only">Toggle navigation</span>
		</button>
		<a class="navbar-brand" href="#">裕达物业</a>
	</div>

	<!-- /.navbar-collapse -->
	</nav><!-- /navbar -->
    <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
