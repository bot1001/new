
<style>
	#h{
		text-align: center;
		background: #3D8060;
		height: 20%;
	}
</style>

<div id="h">
	<div>
		这是首页！
		<?php		
		$statue = [ '欠费' => '0', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金', 7 => '建行' ];
		echo $statue['欠费'];
		?>
	</div>
</div>