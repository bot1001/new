<?php

use yii\helpers\Url;

?>

<?php			
	if(isset($_SESSION['_user']))
	{
		$i = 0;
		$user = $_SESSION['_user'];
		foreach($user['user'] as $u)
	    {
	    	$_u[$i]['community'] = $u[0];
	    	$_u[$i]['count'] = $u['1'];
	    	$i ++;
	    }
	?>
	
	<h4 style="color: #000000">
        <a href="<?= Url::to(['/user/index',
									              'one' => strtotime(date('Y-m-d')), 
									              'two' => time()
									              ]); ?>">
        	今日注册量：<l><?= $user['today']; ?></l>&nbsp;例
        </a>
    </h4>
	<div id="div5">
	
	    <?php
	    	foreach($_u as $us)
	    	{
				?>
		<a href="<?= Url::to(['/user/index',
		  					  'name' => $us['community'],
		  					  'one' => strtotime(date('Y-m-d')),
		  					  'two' => time()
		  				 ]);
								   ?>">
		<div id="div6" style="margin-left: 30px; margin-right: 30px;">
    		<?php
				echo '<div>';
	    		    echo '<i class="fa fa-user text-aqua"></i>'.' '.$us['community'];
				echo '</div>';
				echo "<div id ='div7'>";
				    echo $us['count'].'<g>'.' '.'例'.'</g>';
				echo "</div>";
	    	?>
	    </div>
		</a>
       <?php } ?>
       
    <div id = "div4">
        <a href="<?= Url::to(['/user/index',
									              'one' => strtotime(date('Y-m-d')), 
									              'two' => time()
									              ]); ?>" style="color: #000000">查看全部</a>
    </div>
    </div>
	<?php
	}else{
		?>
	
	<h4 style="color: #000000">
        <a href="<?= Url::to(['/user/index']) ?>">
        	当日注册汇报：空空如也！
        </a>
    </h4>
	
	<?php 
			echo '<h>'.'空空如也！'.'</h>';	
	    }
	?>