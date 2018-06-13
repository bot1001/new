<?php

use yii\helpers\Url;

?>

<style type="text/css">
	#box3{
		background: #DBDFB9;
	}
	.row{
		width: 100%;
/*		height: 250px;*/
		border-radius: 5px;
		background: #A1E7C5;
	}
	
	#tbody tr td{
		font-size: 20px;
		height: 30px;
	}
	span{
		display: block inline;
		width:40%;
	}

</style>

   <h3>
       <a href="<?= Url::to(['/ticket/index']) ?>">
       	当月费用
       </a>
   </h3>
   
<div class="box3">
   <?php
	    $data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
		$community = $_SESSION['home']['community_id'];
		$building = $_SESSION['home']['building_id'];
		$id = $_SESSION['home']['id'];
		  
	    $inv = (new \yii\db\Query) //MySQL语句
			->select('user_invoice.description, user_invoice.invoice_amount as amount, user_invoice.invoice_status as status')
			->from('user_invoice')
			->andwhere(['in', 'community_id', "$community"])
			->andwhere(['in', 'building_id', "$building"])
			->andwhere(['in', 'realestate_id', "$id"]);
		  
		$amount = $inv->all(); //总费用
		  
		$inv01 = array_column($amount, 'amount'); //获取金额
		$sum = array_sum($inv01); //求和
		 $sum01 = 0;
			  
		$invoice = $inv->andwhere(['in', 'year', date('Y')])
			->andwhere(['in', 'month', date('m')])
			->all();
	?>
   <?php foreach($invoice as $key => $in): $in = (object)$in ?>
    <div>
        <span width="20px" style="background: #B5B5B5"><?= $in->description ?></span>
        <span class="col-lg-1"><?php echo $in->amount; $sum01 += $in->amount ?></span>
        <span class="col-lg-4"><?= $data[$in->status] ?></span>
    </div>
    <?php endforeach; ?>
</div>
	<div class="col-lg-1"><?= $sum; ?></div>
	<div class="col-lg-2"><?= $sum01 ?></div>


