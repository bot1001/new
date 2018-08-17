<?php

use yii\helpers\Url;

?>

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
		                echo "<div id ='div7'  style= 'text-align: right'>";
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