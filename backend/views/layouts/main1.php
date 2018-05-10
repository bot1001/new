<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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
<body>
<?php $this->beginBody() ?>

<style>
	#div_m
	{
		background: url(image/bg.png);
		background-size: cover;
		position: fix;
		right: 0;
		left: 0;
		top: 0;
		bottom: 0;
		margin: auto;
	}
	</style>

<div class="wrap" id="div_m">
    <div>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div id="div_login">
        	<?= $content ?>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
