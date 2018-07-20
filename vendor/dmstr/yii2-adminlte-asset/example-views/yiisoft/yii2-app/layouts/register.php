<?php

use yii\helpers\Url;

?>

<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning"><?php echo $today ?></span>
</a>
<ul class="dropdown-menu">
    <li class="header"><h4>本日注册量：<a href="<?php echo Url::to(['/user/index', 
													'one' => $one, 
													'two' => $two
															 ]); ?>"><l><?= $today ?></l>例</a></h4></li>
    <li>
        <!-- inner menu: contains the actual data -->
        <ul class="menu">
            <li>
                  <?php
                  if($user){
					  foreach($community as $key => $comm)//遍历小区
					 {
					     $k = 0;
                     	foreach($user as $keys => $u)//遍历注册信息
                     	{
							$community_id = $u['community_id'];
                     		if($key == $community_id){
                     			$k ++;
                     		}else{
                     			continue;
                     		}
                            if($k > '0'){
                                unset($user[$keys]); //卸掉注册信息
                            }
						}

						if($k == '0'){
                     	    continue;
                        }

                     	?>
                 <a href="<?php
					  if($k > 0){
						  echo Url::to(['/user/index',
											  'name' => $community[$key],
											  'one' => $one,
											  'two' => $two
											 ]);
					  }
					   ?>">
                 <?php
					if($k > 0){
					    echo '<i class="fa fa-users text-aqua"></i>'.$community[$key].'：'.'<l>'.$k.'例'.'</l>'; echo '<br />';
					    $user02[] = [$community[$key], $k];
                        $user03 = ['today' => $today, 'user' => $user02];
                        $_SESSION['_user'] = $user03;
					    }
                    }
					unset($user02);
				} ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="footer"><a href="<?php echo Url::to(['/user/index', 
													'one' => $one, 
													'two' => $two
												   ]); ?>">查看全部</a></li>
</ul>