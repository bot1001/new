<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<style type="text/css">
	#box2-2 {
		font-size: 15px;
		font-weight: 700;
		overflow: auto;
		max-height: 230px;
		width: 100%;
	}
	
	#box2-2 tr td {
		text-align: center;
	}
	
	#box2td,
	#box2td0 {
		background: #F5F5F5;
		border-radius: 3px;
	}
	
	#box2td0 {
		position: relative;
		margin-bottom: 5px;
	}
</style>

<div id="b_2">
	<p>
		<h3>
       <a href="<?= Url::to(['/order/index']) ?>">
       	缴费记录
       </a>
   </h3>
	
	</p>
	<?php
	$data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
	$order = ( new\ yii\ db\ Query )->select( 'order_basic.id as id,order_basic.order_id as order_id, order_basic.create_time as create_time,
			order_basic.order_type as type, order_basic.payment_time as payment_time,
			order_basic.payment_gateway as gateway, order_basic.description as description,
			order_basic.order_amount as amount, order_basic.status as status,
			order_relationship_address.address as address, user_data.real_name as name' )->from( 'order_basic' )->join( 'inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id' )->join( 'inner join', 'user_data', 'user_data.account_id = order_basic.account_id' )->where( [ '=', 'order_basic.status', '2' ] )->orderBy( 'payment_time DESC' )->limit( 20 )->all();
	?>

	<div style="height: 250px; width: 100%; overflow: auto">
		<table id="box2-2">
			<?php foreach($order as $or): $or = (object)$or ?>
			<tr>
				<td>
					<div id="box2td">
						<l>
							<?= $or->order_id; ?>
						</l>
					</div>
				</td>
				<td>
					<div id="box2td">
						<?= date('Y-m-d H:i:s', $or->payment_time); ?>
					</div>
				</td>
				<td>
					<div id="box2td">
						<?php if(!empty($or->gateway)){
               	        echo $data[$or->gateway];
                    }?>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<div id="box2td0"><a href="<?= Url::to(['/order/view', 'id' => $or->id]) ?>"><?= $or->address; ?></a>
					</div>
				</td>
				</td>
				<td align="right">
					<div id="box2td0">
						<?= $or->amount; ?>
					</div>
				</td>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	
	<div id="new">
		<?= Html::a('更多','/order/index', ['class' => 'btn btn-info', 'title' => '更多记录']) ?>
	</div>
</div>