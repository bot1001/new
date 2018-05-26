<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
    <span class="hidden-xs"><?php echo $session['name'] ?></span>
</a>
<ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
             alt="User Image"/>
        <p>
			当前小区：<l><?php if(count($a) == 1)
                    {
                    	echo $community[$a['0']];
                    }else{
                    	echo '点击查看';
                    } ?></l>
            <small>角色：<?php if(isset($r_name[$r_id]))
                        {
		                     echo ($r_name[$r_id]);
                        } ?></small>
        </p>
    </li>
    <!-- Menu Body -->
    <li class="user-body">
        <div class="col-xs-4 text-center">
            <a href="#">粉丝</a>
        </div>
        <div class="col-xs-4 text-center">
            <a href="#">点击率</a>
        </div>
        <div class="col-xs-4 text-center">
            <a href="#">朋友</a>
        </div>
    </li>
    <!-- Menu Footer-->
    <li class="user-footer">
        <div class="pull-left">
           <?= Html::a(
                '修改密码',
                ['/sysuser/change'],
                ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
            ) ?>
            
        </div>
        <div class="pull-right">
            <?= Html::a(
                '退出',
                ['/site/logout'],
                ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
            ) ?>
        </div>
    </li>
</ul>