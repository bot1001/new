<?php

use yii\helpers\Url;

?>

<?php 
	if(isset($_SESSION['_ticket']['account'])){
		
    //遍历并重组数组
    if(isset($_SESSION['_ticket']['ticket']))
    {
    	$k = 0;
    	$ticket = $_SESSION['_ticket']['ticket'];
    	foreach($ticket as $t)
    	{
    		$_t[$k]['community'] = $t[0]['0'];
    		$_t[$k]['community_id'] = $t['4']['0'];
    		$_t[$k]['building'] = $t['1']['0'];
    		$_t[$k]['room'] = $t['2']['0'];
    		$_t[$k]['time'] = $t['3']['0'];
    		$k ++;
    	}	
    }
?>

   <h4 style="color: #000000">
       <a href="<?php echo Url::to(['/ticket/index','ticket_status' => '1']) ?>">
       	未处理投诉量：<l><?php echo count($_t); ?></l>例
       </a>
   </h4>
   
   <div id="div5">
   	<?php
   	if(isset($_t))
   	{ 
   	    foreach($_t as $ti)
   	    	{
               if(isset($ticket)){
   				?>
   		    
   		    <a href="<?php 
   					    if(!isset($_t)){
   					    	echo '';
   					    }else{
   					    	echo Url::to(['/ticket/index',
   					    							'community' => $ti['community_id'],
   					    							'building' => $ti['building'],
   					    							]);
   					    }
   					?>">
				<div id="div10">
       		        <?php
               	    	echo '<i class="fa fa-users"></i>'.' '.
							$ti['community'].' '.
							$ti['building'].' '.
							$ti['room'];
				        echo '<br />'.' '.date('Y-m-d H:i:d', $ti['time']);
   				    ?>
                   </div>
   	        </a>
           <?php
              	}
           }
      } ?>
   <div id = "div4">
       <a href="<?php echo Url::to(['/ticket/index','ticket_status' => '1']) ?>" style="color: #000000">查看全部</a>
   </div>
   
   	<?php
		unset($_t);
   		}else{
		?>
   <h4 style="color: #000000">
       <a href="<?php echo Url::to(['/ticket/index']) ?>">
       	业主投诉情况汇报：
       </a>
   </h4>
		<?php
   			echo '<h>'.'心情极好'.'</h>';
   		}
   		?>
</div>