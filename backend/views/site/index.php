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
	  
	  #box5{
		  height: 300px;
		  width: 400px;
		  background: #81EBDF;
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
		  margin-bottom: 5px; #div 上下之间的距离
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
		  margin-left: 250px;
		  margin-top: -28px; 
	  }
	  
	  #div8{
		  height: 250px;
		  width: 385px;
		  overflow: auto; 
		  border-radius:10px;
		  position: relative;
		  left: 2%;;
	  }
	  
	  #in01{
		  background: #7ABD77;
		  width: 100%;;
		  border-radius: 3px;
		  text-align: left;
		  text-align-last:justify;
		  font-size: 16px;
	  }
	  
	  #in02{
		  width: 100%;
		  height: 100%;
		  text-align-last:justify;
		  border-radius: 3px;
		  text-align: left;
		  font-size: 16px;
		  background: #A9E0D3;
	  }
	  
	  #divin{
		  position: relative; width: 40%;
	  }
	  #in03{
		  position: relative;
		  width: 75%;
		  background: #76DF9F;
	  }
	  #in04{
		  height: 98px;
		  width: auto;
		  background: #B44143;
		  border-radius: 10px;
		  position: relative;
		  background: url('/image/logo.png');
		  background-size: 98px 100px;
	  }
	  
	  #in05{
		  text-align: center;
		  background: #7ABD77;
		  border-radius: 5px;
		  height: 100%;
		  width: 100%;
	  }
	  
	  table{
		  background: #88DCDB;
		  width: 100%;		  
	  }
	  
	  #in06{
		  width: 180px;
		  background: #A9E0D3;
		  border-radius: 5px;
		  margin-bottom: 2px; #div 上下之间的距离
	  }	 
	  
	  #in08{
		  display: inline;
		  background: #A9E0D3;
		  border-radius: 5px;
		  margin-bottom: 2px; #div 上下之间的距离
	  }	 
	  
	  #in07{
		  width: 200px;
		  background: #7ABD77;
		  border-radius: 5px;
		  margin-bottom: 5px; #div 上下之间的距离
		  text-align: center;
		  margin: auto;
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
		        	业主投诉情况汇报：
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
								echo '<i class="fa fa-flag-o"></i>'.' '.$or->address;
				                echo "<div id ='div7'>";
						           echo $or->order_amount.'<g>'.' '.'元'.'</g>';
						        echo "</div>";
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
		       	    <?php echo '账户信息'; ?>
  		       </a>
  		   </h4>
  		   
  		   <?php
		       if(isset($_SESSION['user']))
		       {
			       $session = $_SESSION['user'];
			       $name = array_unique(array_column($session, 'name'));
		           $Role = array_unique(array_column($session, 'Role'));
				   $comment = array_unique(array_column($session,'comment'));
		       }else{
			       $name = $Role = '';
		       }
		       
		       if(isset($_SESSION['community_name']))
			   {
			       $community_name = $_SESSION['community_name'];
		       }else{
			       $community_name = '';
		       }
		   ?>
			   <div id="div8"  class="row">
	                <table border="0">
	                    <tr>
	                    	<td width = "23%"><div id="in01" class="col-lg-1">用户名:</div></td>
	                    	<td width = "50%"><div id="in02" class="col-lg-1">
	                    	<?php 
								foreach($name as $n) {
									echo $n;
								}
								?>
	                    	</div></td>
	                    	<td width = "27%" rowspan=3><div id="in04"></div></td>
	                    </tr>
	                    <tr>
	                    	<td><div id="in01" class="col-lg-1">职位:</div></td>
	                    	<td><div id="in02" class="col-lg-1">
	                             	<?php
								        $count = count($Role);
								        foreach($Role as $key => $r)
								        {
								        	if($count == '1'){
								        		echo $r;
								        	}else{
								        		if($key+1 === $count){
								        			echo $r;
								        		}else{
								        			echo $r.'<l>'.'兼'.'</l>';
								        		}
								        	}
								        	unset($r);
								        }
					          			?>
                                 </div>
                             </td>
	                    </tr>
	                    <tr>
	                    	<td colspan="2"><div id="in05">关联小区</div></td>
	                    </tr>
	                    <tr>
	                    	<td colspan="3">
	                    		<?php 
								foreach($community_name as $name)
								{
									?>
										<div id="in06" class="col-lg-1">
										     <?php
								                  echo $name['community_name'];
								              ?>
								         </div>
									<?php }
								                ?>
	                    	</td>
	                    </tr>
	                    <tr>
	                    	<td colspan="3">
	                    		<div id="in07" style="text-align: center">账户说明</div>
	                    	</td>
	                    </tr>
	                    
	                    <tr>
	                    	<td colspan="3" id="in06">
	                    		<?php 
								if(isset($comment))
								{
									foreach($comment as $key => $_comment)
									{
										$key += 1;
										echo $key.'、'.$_comment;
									}
								}
								?>
	                    	</td>
	                    </tr>
	                </table>
		          
			   </div>
   		   
       </div>
      
	   <div id="box5" class="col-lg-3">
	       <h4 style="color: #000000">
    	       <a href="#">
		       	    <?php echo 'Message'; ?>
  		       </a>
   		   </h4>	   	
	   </div>

	<a href="<?php //echo Url::to(['/user-invoice/search']); ?>"> <h5><!-- 缴费统计 --></h5></a>
	<a href="<?php //echo Url::to(['/user-invoice/sum']); ?>"> <h5><!-- 新缴费统计 --></h5></a>
 </div>