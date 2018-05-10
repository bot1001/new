<?php

use app\models\UserInvoice;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 300000);
});
JS;
$this->registerJs($script);

/* @var $this yii\web\View */

$this->title = '裕达物业';
?>

  <style>
	  h4{
		  color: #0DE842;
		  font-size: 24px;
		  text-align:center;
		  font-style: italic;
	  }
	  
	  h{
		  width: 100px;
		  font-size: 20px;
		  text-align: center;
		  border-radius:5px;
		  position: relative;
		  top: 40%;
		  left: 100px;
		  background: #E5F5F3
	  }
	  
	  g{
		  color: #000000;
	  }
	  
	  #box1{
		  height: 300px;
		  width: 400px;
		  background: #BEE9E6;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #box2{
		  height: 300px;
		  width: 400px;
		  background: #9BE1B0;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #box3{
		  height: 300px;
		  width: 400px;
		  background: #89FDBE;
		  border-radius: 18px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  
	  #box4{
		  height: 300px;
		  width: 400px;
		  background: #81D2EB;
		  border-radius: 18px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #div4{
		 color: aqua;
		  background: #DBDADA;
		  font-size: 20px;
		  text-align: center;
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  margin: auto;
		  border-radius: 5px;
		  width: 150px;
		  
	  }
	  #div5{
		  height: 210px;
		  overflow: auto;
	  }
	  
	  #div6{
		  height: auto;
		  font-size: 19px;
		  background-color: #E5F5F3;
		  border-radius: 5px;
		  margin-bottom:5px; #div 之间的距离
	  }
	  
	  #div10{
		  height: auto;
		  font-size: 19px;
		  text-align: center;
		  background-color: #E5F5F3;
		  border-radius: 5px;
		  margin-bottom:5px; #div 之间的距离
	  }
	  #div7{
		  color: red;
		  position: relative;
		  margin-left: 200px;
		  margin-top: -25px; 
	  }
	  
</style>
  
   <div style="background-color: #E5F5F3;border-radius: 20px;">
    
    	<div id="box1" class="col-lg-3">
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
				$community_id = array_column($_t, 'community_id');
			?>

		    <h4 style="color: #000000">
    	        <a href="<?php echo Url::to(['/ticket/index','name' => '1', 'community_id' => $community_id]) ?>">
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
                <a href="<?php echo Url::to(['/ticket/index','name' => '1', 'community_id' => $community_id]) ?>" style="color: #000000">查看全部</a>
            </div>
            
    	    	<?php
					unset($_t);
		    		}else{
					?>
	    	<h4 style="color: #000000">
    	        <a href="<?php echo Url::to(['/ticket/index']) ?>">
		        	未处理投情况汇报：
  		        </a>
   		    </h4>
	    			<?php
		    			echo '<h>'.'心情极好'.'</h>';
		    		}
		    		?>
			</div>
	    </div>
	    
    	<div id="box2" class="col-lg-3">
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
    	        <a href="<?php echo Url::to(['/user/index', 
											              'one' => strtotime(date('Y-m-d')), 
											              'two' => time()
											              ]); ?>">
		        	今日注册量：<l><?php echo $user['today']; ?></l>&nbsp;例
  		        </a>
   		    </h4>
			<div id="div5">
			
			    <?php
			    	foreach($_u as $us)
			    	{
						?>
				<a href="<?php 
				    echo Url::to(['/user/index',
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
                <a href="<?php echo Url::to(['/user/index', 
											              'one' => strtotime(date('Y-m-d')), 
											              'two' => time()
											              ]); ?>" style="color: #000000">查看全部</a>
            </div>
            </div>
			<?php
			}else{
				?>
			
			<h4 style="color: #000000">
    	        <a href="<?php echo Url::to(['/user-invoice/index']) ?>">
		        	当日注册汇报：空空如也！
  		        </a>
   		    </h4>
			
			<?php 
					echo '<h>'.'空空如也！'.'</h>';	
			    }
			?>
   	        
    	</div>
    	
    	<div id="box3" class="col-lg-3">
    		<?php
			if(isset($_SESSION['order']))
			{
				$order = $_SESSION['order'];
				$one = strtotime(date('Y-m-d'));
				$two = time();
				?>
				
				<h4 style="color: #000000">
    	            <a href="<?php echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]) ?>">
		            	今日订单量：<l><?php echo $order['count']; ?></l>&nbsp;条
  		            </a>
   		        </h4>
   		    <div id="div5">
				<?php foreach($order['order'] as $or):$or = (object)$or; ?>
				
			       <a href="<?php echo Url::to(['/user-invoice/index', 'order_id' => $or->order_id]) ?>">
		                <div id="div6">
		                	<?php
		                	    	$add = explode('-', $or->address);
		                	    	if(count($add) == 4) 
		                	    	{
		                	    		echo '<i class="fa fa-flag-o"></i>'.' '.$add['0'].' '.$add['1'].' '.$add['2'].'单元'.' '.end($add);
		                	    	}elseif( count($add) == 3){
		                	    		echo '<i class="fa fa-flag-o"></i>'.' '.$add['0'].' '.$add['1'].' '.'1 单元'.' '.end($add);
		                	    	}else{
										echo '<i class="fa fa-flag-o"></i>'.' '.$or->address;
									}
		                	   ?>
		                </div>
			       </a>
		       <?php endforeach;?>
		   </div>	   
		   
		   <div id = "div4">
                <a href="<?php 
							 if(empty($a)){
								 echo Url::to(['/order/index', 'one' => $one, 'two' => $two]);
							 }else{
								 echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]);
							 }
							 ?>" style="color: #000000">查看全部</a>
            </div>
            
            <?php
			   }else{
				?>
		   	<h4 style="color: #000000">
    	            <a href="#">
		            	当日缴费情况
  		            </a>
   		        </h4>
   		        
			 <?php 
				echo '<h>'.'仍需努力！'.'</h>';
			} 
			?>
		    
    	</div>
      <div id="box4" class="col-lg-3">
      	  <h4 style="color: #000000">
    	            <a href="#">
		            	<?php echo 'information'; ?>
  		            </a>
   		        </h4>
      </div>

	<a href="<?php //echo Url::to(['/user-invoice/search']); ?>"> <h5><!-- 缴费统计 --></h5></a>
	<a href="<?php //echo Url::to(['/user-invoice/sum']); ?>"> <h5><!-- 新缴费统计 --></h5></a>
 </div>