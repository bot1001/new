<?php

use yii\helpers\Url;

$this->title = '缴费预览';
$this->params['breadcrumbs'][] = ['label' => '房屋缴费', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
	
	#center,#prepay, #right, #left {
		height: 25px;
		font-size: 20px;
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
/*		margin-left: 400px;*/
	}
</style>

<div>
	<table width="500" border="1" align="center" style="position: relative; margin-top: 20px;">
		<thead>
			<tr>
				<th id="prepay">序号</th>
				<th id="prepay">年份</th>
				<th id="prepay">月份</th>
				<th id="prepay">名称</th>
				<th id="prepay">合计</th>
				<th id="prepay">备注</th>
			</tr>
		</thead>
		<tbody>
			<?php $sum = 0; $sale = 0;$i = 0; ?>
			<?php foreach($prepay as $p): $p= (object)$p; ?>
			<tr>
				<td id="center">
					<?php $i ++; echo $i; ?>
				</td>
				<td id="center">
					<?= $p->year; ?>
				</td>
				<td id="center">
					<?= $p->month; ?>
				</td>
				<td id="center">
					<?= $p->description; ?>
				</td>
				<td id="center">
					<?php echo $p->amount; 
					$sum += $p->amount; //计算合计金额
					if($p->sale == '1'){
                    	$sale += $p->amount; //统计优惠金额
                    } ?>
				</td>
				<td id="center">
					<?= $p->notes; ?>
				</td>
			</tr>
			<?php endforeach; ?>

			<tr>
				<td id='right'>共:&nbsp;&nbsp;&nbsp;</td>
				<td id='center'><?= $i.'条' ?></td>
				<td id='right'>优惠</td>
				<td id='center'><?= number_format($sale, 2) ?></td>
				<td id='right'>合计:&nbsp;&nbsp;&nbsp;</td>
				<td id="left">&nbsp;&nbsp;&nbsp;
					<?= number_format($sum, 2).'元' ?>
				</td>
			</tr>
		</tbody>
	</table>

	<div id="pp">
		<a href="<?= Url::to(['/invoice/new', 'cost' => $cost, 'year' => $year, 'month' => $month, 'id' => $id, 'amount' => $sum-$sale]); ?>">Going…</a>
	</div>
</div>