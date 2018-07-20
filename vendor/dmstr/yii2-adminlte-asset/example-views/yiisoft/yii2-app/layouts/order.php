<?php

use yii\helpers\Url;

?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-flag-o"></i>
    <span class="label label-danger"><?php echo $o_count; ?></span>
</a>
<ul class="dropdown-menu">
	<li class="header"><h4>当日订单数量：<a href="<?php echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]) ?>"><l><?php echo $o_count; ?> </l>条</a></h4></li>
    <li>
        <!-- inner menu: contains the actual data -->
        <ul class="menu">
            <li>
               <?php foreach($order as $key => $o){?>
				    <a href="<?php echo Url::to(['/user-invoice/index', 'order_id' => $o['order_id']]) ?>">
		        
			        <?php 
                         echo '<i class="fa fa-users text-aqua"></i>'.' '.
                             $o['address'].' '.
                             date('H:i', $o['payment_time']).' '.
                             '<l>'.$o['order_amount'].'</l>';
					     $address = $o['address'];
						 $time = $o['payment_time'];
						 $order_amount = $o['order_amount'];
						 $order_id = $o['order_id'];
						 $ord[] = ['address' => $address, 'time' => $time, 'order_amount' => $order_amount, 'order_id' => $order_id];
						?>
				
				</a>
              <?php }
				if(isset($ord)){
					$_SESSION['order'] = ['order' =>$ord, 'count' => $o_count];
				}
				    unset($order);									
				?>
              
               <!-- Task item 
                <a href="#">
                    <h3>
                        Design some buttons
                        <small class="pull-right">50%</small>
                    </h3>
                    <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 50%"
                             role="progressbar" aria-valuenow="20" aria-valuemin="0"
                             aria-valuemax="100">
                            <span class="sr-only">20% Complete</span>
                        </div>
                    </div>
                </a>-->
            </li>
        </ul>
    </li>
    <li class="footer">
        <a href="<?php 
				 if(empty($a)){
					 echo Url::to(['/order/index', 'one' => $one, 'two' => $two]);
				 }else{
					 echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]);
				 }
				  ?>">查看全部</a>
    </li>
</ul>