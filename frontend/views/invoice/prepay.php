<?php

use yii\helpers\Url;

$this->title = '缴费预览';
$this->params['breadcrumbs'][] = ['label' => '房屋缴费', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
	
	table th, table td {
		height: 25px;
	}
	
	th {
		text-align: center;
	}
	
	#center {
		text-align: center;
	}
	
	#right {
		text-align: right;
		position: relative;
	}
	
	#pp {
		margin: auto;
		font-size: 25px;
		text-align: center;
		line-height: 60px;
		width: 116px;
		height: 54px;
		background: url(/image/timg.jpg);
		background-size: 116px 54px;
		border-radius: 30px;
		margin-top: 10px;
	}
</style>

<div>
	<table border="1" align="center" style="position: relative;font-size: 20px; width: 800px;">
		<thead>
			<tr>
				<th>序号</th>
				<th>年份</th>
				<th>月份</th>
				<th>名称</th>
				<th>合计</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<?php $sum = 0; $sale = 0;$i = 0; ?>
			<?php foreach($prepay as $p): $p= (object)$p; ?>
			<tr>
				<td id="center"><?php $i ++; echo $i; ?></td>
				<td id="center"><?= $p->year; ?></td>
				<td id="center"><?= $p->month; ?></td>
				<td><?= $p->description; ?></td>
				<td id="right">
					<?php echo $p->amount; 
					$sum += $p->amount; //计算合计金额
					if($p->sale == '1'){
                    	$sale += $p->amount; //统计优惠金额
                    } ?>
				</td>
				<td id="center"><?= $p->notes; ?></td>
			</tr>
			<?php endforeach; ?>

			<tr>
				<td id='right'>共:&nbsp;&nbsp;&nbsp;</td>
				<td id='center'><?= $i.'条' ?></td>
				<td id='center'>优惠</td>
				<td id='center'><l><?= number_format($sale, 2); ?></l></td>
				<td id='right'>合计:&nbsp;&nbsp;&nbsp;</td>
				<td><?= number_format($sum, 2).'元' ?></td>
			</tr>
		</tbody>
	</table>

	<div id="pp">
		<a href="<?= Url::to(['/invoice/new', 'cost' => $cost, 'year' => $year, 'month' => $month, 'id' => $id, 'amount' => $sum-$sale]); ?>">Going…</a>
	</div>
</div>