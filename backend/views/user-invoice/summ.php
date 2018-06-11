<?php

?>

<style type="text/css">
	td, th {
		height: 30px;
		text-align: center;
	}
	 table tr:hover{background-color: #dafdf3;}
	 table tr:nth-child(odd){  
        background: #efefef;  
    }  
	
    table{
		width: 100%;
        border-collapse:collapse;
		position: relative;     
        bottom: 15px;
    }
	thead {
		font-weight: bold;
		font-size: 15px;
		text-align: center;
	}
	#div10{
		font-size: 20px;
		font-weight: 1000;
		background: #FFFFFF;
		border-radius: 5px;
		position: relative;
		top: 5px;
		height: auto;
		width: auto;
	}
	
	
	#div12{
		font-size: 15px;
		font-weight: 1000;
		color:rgba(0,0,0,0.7);;
		background: #FFFFFF;
		text-align:right;
		border-radius: 5px;
		position: relative;
		top: -5px;
	}
	#sum{
		height: 640px;
	}
		
</style>

<?php $this->title = '按小区'; ?>

<div id="div12">
	<div class="row">
		<div class="col-lg-3" align="left"><?= '总计：'.'<l>'.$sum.'</l>'.'元；'.' '.'共：'.'<l>'.$count.'</l>'.'条'; ?></div>
	    <div class="col-lg-9"><?= '起始时间：'.$from.'&nbsp&nbsp&nbsp&nbsp'.'截止时间：'.$to; ?></div>
	</div>
</div>
<div id="sum">
	
<?php
if($data)//判断是否存在缴费数据
{
	$i = 0;
	$status = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金', '7' => '建行' ];
	?>
	<table border="1">
		<thead>
			<th><input type = "checkbox" name="checkbox[]"></th>
			<th>序号</th>
			<th>小区</th>
			<th>楼宇</th>
			<th>单元</th>
			<th>房号</th>
			<th>年份</th>
			<th>月份</th>
			<th>详情</th>
			<th>金额</th>
			<th>订单编号</th>
			<th>支付时间</th>
			<th>状态</th>
		</thead>
	
		<tbody>
		<?php foreach($data as $d): $d = (object)$d ?>
			<tr>
				<td><input type = "checkbox" name="checkbox[]"></td>
				<td><?php $i +=1; echo $i; ?></td>
				<td><?= $d->community; ?></td>
				<td><?= $d->building; ?></td>
				<td><?= $d->number; ?></td>
				<td><?= $d->name; ?></td>
				<td><?= $d->year; ?></td>
				<td><?= $d->month; ?></td>
				<td><?= $d->description; ?></td>
				<td><?= $d->amount; ?></td>
				<td><?= $d->order; ?></td>
				<td><?php
	                    if($d->payment_time == ''){
	                    	echo '';
	                    }else{
	                    	echo date('Y-m-d H:i:s', $d->payment_time);
	                    }
	                ?></td>
				<td><?= $status[$d->status]; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	<br />
	
</table>

</div>

<div id="div10" class="footer">
	<div>
		<div id="page">
			<?php
	            echo yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
	        ?>
	    </div>
	</div>
</div>

<?php } ?>
