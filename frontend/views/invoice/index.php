<?php

$this->title = '物业缴费';

?>

<div style="height: 100%">
<style type="text/css">
	#mail {
		font-size: 20px;
		border-radius: 5px;
		background: #E9E9E9;
		color: #000000
	}
	
	#div6 {
		color: #ffffff;
	}
	
	#div2 {
		color: #ffffff;
	}
	
	table tr:hover {
		background-color: #dafdf3;
	}
	
	#all {
		overflow-x: auto;
		overflow-y: auto;
		height: 500px;
		width: 100%;
	}
	
	#name {
		font-size: 35px;
	}
	
	#amount {
		color: red;
		font-size: 35px;
	}
	
</style>

<div id="all">
	<?php $amount = 0; ?>
	<?php foreach($invoice as $in): $in = (object)$in; ?>
	<div id="mail">
		<div id="div<?php echo $in->status ?>">
			<table width="100%">
				<tr>
					<td>
						<?php echo $in->year.'年'.' '.$in->month.'月'.' '.$in->description;?>
					</td>
					<td align="right" style="color: darkorange">
						<?php $a = $in->amount;
							 if($in->status == 0){
							 	$amount += $a;
							 }
							 echo $a;
							 ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<table style="font-size: 25px; width: 100%; text-align: center">
	<tr>
		<td style=" width: 33%; background: #AADBBD; text-align: right">合计：</td>
		<td style="background: #AADBBD"><?php echo $amount ?></td>
		<td width="35%" style="color: red; background: #FDC6C6"><a href="">立即缴费</a></td>
	</tr>
</table>
</div>