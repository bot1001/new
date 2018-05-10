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
		  background: #0EEB5E;
		  border-radius: 20px;
		  position: relative;
		  margin-top: 1%;
		  margin-left: 1%;
	  }
	  #box3{
		  height: 300px;
		  width: 400px;
		  background: #0EEB5E;
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
		  height: 30px;
		  font-size: 18px;
		  background-color: #E5F5F3;
		  border-radius: 5px;
		  margin-bottom:5px; #div 之间的距离
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

		<h4 style="color: #000000"><a href="<?php echo Url::to(['/ticket/index','name' => '1', 'community_id' => $community_id]) ?>">
			未处理投诉量：<l><?php echo count($_t); ?></l>例</a></h4>
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
							<div id="div6">
		        		        <?php
	                    	    	echo $ti['community'].' '.$ti['building'].' '.$ti['room'].' '.date('Y-m-d H:i:d', $ti['time']);
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
		    		}else{
		    			echo '<h>'.'坐等业主投诉……'.'</h>';
		    		}
		    		?>
			</div>
	    </div>
	    
    	<div id="box2" class="col-lg-3">
    		<?php
			    print_r($a = $_SESSION['community']);
			echo '<pre />';
			  print_r(date(time()));
			?>
    	</div>
    	
    	<div id="box2" class="col-lg-3">
    		
    	</div>
       
       <div class="jumbotron">
    
       </div>

	<a href="<?php //echo Url::to(['/user-invoice/search']); ?>"> <h5><!-- 缴费统计 --></h5></a>
	<a href="<?php //echo Url::to(['/user-invoice/sum']); ?>"> <h5><!-- 新缴费统计 --></h5></a>
</div>