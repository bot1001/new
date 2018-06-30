<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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

<body style="background: #F7F6F0">
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->render( 'header.php' ) ?>
	<p></p>
    <table width="1190" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td>
        <?= $content ?>
        </td>
      </tr>
     </table>    
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
