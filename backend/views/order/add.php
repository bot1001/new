<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = '缴费预览';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="user-order-pay">

	<style type="text/css">
		th{
			text-align:center;
		}
						
		table{
			text-align:center;
			margin:auto;
		}
		
		#div0{
			text-align: center;
			font-size: 24px;
			color: #FFFFFF;
			background: url(/image/timg.jpg);
			width: 116px;
			height: 54px;
			background-size: 116px 54px;
			border-radius: 30px;
			position: relative;
			top: 25px;
			margin: auto;
		}
		
		h{
			position: relative;
			top: 12px;
		}
		</style>
							
		<table width="768" border="1" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<th>序号</th>
					<th colspan="5">详情</th>
					<th>应收</th>
					<th>实收</th>
				</tr>
				<?php foreach($invoice as $k =>$i): $i = (object) $i?>
				<tr>
					<td width="7%"><?php echo $k+1; ?></td>
					<td width=""><?php
						$comm = $i->community;
				        echo $comm;
						?></td>
					<td width="8%"><?php 
						$building = $i->building;
						echo $building; ?></td>
						
					<td><?= $i->name ?></td>
						
					<td width="9%"><?php echo $i->year; ?>年</td>
					<td width="8%"><?php echo $i->month; ?>月</td>
					<td width="" align="left"><?php echo $i->description; ?></td>
					<td width="9%"><?php echo $i->amount; ?></td>
					<td width="9%"><?php echo $i->amount; ?></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="2">共：<?php echo $n; ?>条</td>
					<td align="right" colspan="3">活动优惠：<?php $Y = '0%'; echo $Y; ?></td>
					<td align="right">合计：</td>
					<td colspan="2"><?php $c = $m; echo $c; ?>元</td>
				</tr>
			</tbody>
		</table>
		
		<a href="<?=Url::to(['/order/add','c' => $c,'id' => $id,'address' => $address, 'c_id' => $c_id ]); ?>">
		    <div id="div0"><h>GOing...</h></div>
		</a>
	</table>
</div>