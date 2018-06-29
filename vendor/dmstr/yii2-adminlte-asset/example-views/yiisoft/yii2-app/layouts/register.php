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
															 ]); ?>"><l><?php echo $today ?></l>个</a></h4></li>
    <li>
        <!-- inner menu: contains the actual data -->
        <ul class="menu">
            <li>
                  <?php
                  if($user){
					  //遍历小区
					  foreach(array_unique($u_c) as $key => $comm)
					 {
						 //遍历注册信息
                     	foreach($user as $keys => $u)
                     	{
							$community_id = $u['community_id'];
                     		if($comm == $community_id){
                     			$k[] = $u;
                     		}else{
                     			continue;
                     		}
							$c_c = count($k); //统计数量
							
						}	
						unset($k); //释放数组
                     	?>
                 <a href="<?php 
					  if($c_c > 0){
						  echo Url::to(['/user/index',
											  'name' => $community[$comm],
											  'one' => $one,
											  'two' => $two
											 ]);
					  }
					   ?>">
                 <?php
					if($c_c > 0){
					    echo '<i class="fa fa-users text-aqua"></i>'.$community[$comm].'：'.'<l>'.$c_c.'个'.'</l>'; echo '<br />';
					    $user02[] = [$community[$comm], $c_c];
					    }
                    }
					$user03 = ['today' => $today, 'user' => $user02];
					$_SESSION['_user'] = $user03;
					unset($user02);
				}
				
				?>
                </a>
            </li>
        </ul>
    </li>
    <li class="footer"><a href="<?php echo Url::to(['/user/index', 
													'one' => $one, 
													'two' => $two
												   ]); ?>">查看全部</a></li>
</ul>