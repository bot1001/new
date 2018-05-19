<?php

use yii\helpers\Url;

?>

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