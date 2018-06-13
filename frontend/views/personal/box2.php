<?php

use yii\helpers\Url;

?>

<style type="text/css">
	#box2-1{
		height: 260px;
		  width: 370px;
		  overflow: auto; 
		  border-radius:10px;
		  position: relative;
		  left: 2%;
	}
	
	table tr{
		height: 25px;
	}
	#box2-2{
	}
</style>

<div id="b_2">
	<h3>
       <a href="<?= Url::to(['/order/index']) ?>">
       	缴费记录
       </a>
   </h3>
   
   <?php
	    $data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
	    $order = (new \yii\db\Query)
			->select('order_basic.id as id,order_basic.order_id as order_id, order_basic.create_time as create_time,
			order_basic.order_type as type, order_basic.payment_time as payment_time,
			order_basic.payment_gateway as gateway, order_basic.description as description,
			order_basic.order_amount as amount, order_basic.status as status,
			order_relationship_address.address as address, user_data.real_name as name')
			->from('order_basic')
			->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
			->join('inner join', 'user_data', 'user_data.account_id = order_basic.account_id')
			->where(['=', 'order_basic.status', '2'])
			->orderBy('payment_time DESC')
			->limit(20)
			->all();
	?>
	
	<div id="box2-1">
       <table id="box2-2" border="1px" height="300px">
       <?php foreach($order as $or): $or = (object)$or ?>
       	<tr>
       		<td align="center" width="35%"><l><?= $or->order_id; ?></l></td>
       		<td align="center" width="120"><?= date('Y-m-d H:i:s', $or->payment_time); ?></td>
       		<td align="center" width="10%"><?php if(!empty($or->gateway)){
              	echo $data[$or->gateway];
              }?></td>
       	</tr>
       	
       	<tr>
       		<td colspan="2" align="center"><a href="<?= Url::to(['/order/view', 'id' => $or->id]) ?>"><?= $or->address; ?></a></td>
       		<td align="right"><?= $or->amount; ?></td>
       	</tr>
       	<?php endforeach; ?>
       </table>
   </div>
</div>
