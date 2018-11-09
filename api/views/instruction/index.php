<?php

/* @var $this yii\web\View */
/* @var $model app\models\UserAccount */

$this->title = $model['title'];
?>
<style>
    image, img{
        width: 100%;
        border-radius: 15px;
    }
</style>

<div class="container">
    <?= $model['content'] ?>
</div>

