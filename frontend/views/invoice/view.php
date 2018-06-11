<?php

use yii\helpers\Url;

$this->title = '缴费预览';
$this->params['breadcrumbs'][] = ['label' => '房屋缴费', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
	#center, th, #right, #left {
		height: 25px;
		font-size: 20px;
		text-align: center;
	}
	th, td{
		text-align: center;
		font-size: 20px;
	}
	
	#pp {
		margin: auto;
		font-size: 25px;
		line-height: 60px;
		width: 116px;
		height: 54px;
		background: url(/image/timg.jpg);
		background-size: 116px 54px;
		border-radius: 300px;
		margin-top: 10px;
	}
	
	#view{
		max-height: 600px;
		width: 800px;
		overflow-y: auto;
		margin: auto;border-radius: 10px;
	} 
</style>

<div id="view">
	<table border="1" width=780px;>
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
			<?php
			$sum = 0; //缴费综合
			$sale = 0; //优惠标记
			$i = 0; //序号
			$sale_sum = 0; //优惠金额
			?>
			<?php foreach($invoice as $p): $p= (object)$p; ?>
			<tr>
				<td>
					<?php $i ++; 
					echo $i;
					if($p->description == "物业费"){
		    	    	if($p->year > date('Y'))
		    	    	{
		    	    		$sale ++; //判断预交优惠信息
		    	    	}elseif($p->year == date('Y')){
							if($p->month > date('m'))
		    	    		{
		    	    			$sale ++; //判断预交信息
		    	    		}
						}
		    	    }
					?>
				</td>
				
				<td><?= $p->year; ?></td>
				
				<td><?= $p->month; ?></td>
				
				<td><?= $p->description; ?></td>
				
				<td>
					<?php 
					echo $p->amount;
					
					$sum += $p->amount; //计算合计金额
					if( $sale > 0 && $sale%13 == '0'){
                    	 $sale_sum += $p->amount; //统计优惠金额
                    };
					?>
				</td>
				
				<td><?= $p->notes; ?></td>
			</tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="2">共：<?= $i.'条' ?></td>
				<td>优惠</td>
				<td><?= number_format($sale_sum, 2).'元'; ?></td>
				<td id="left" colspan="2">&nbsp;&nbsp;&nbsp;
					<?= number_format($sum-$sale_sum, 2).'元' ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div id="pp">
	<a href="<?= Url::to(['/invoice/pay', 'amount' => $sum-$sale_sum]); ?>"><l style="color: white">Going…</l></a>
</div>
