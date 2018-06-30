<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<style type="text/css">
	#box3{
		background: #DBDFB9;
	}
		
	#box302{
		background: #94D3D8;
		margin-right: 5px;
		border-radius: 5px;
	}
	#box3table{
		font-size: 20px;
		width: 100%;
	}
	
	#box3table tr{
		height:40px;
	}
	
	#box303{
		position: absolute;
		background: #E8E8E8;
		bottom: 20px;
		font-size: 20px;
		width:330px;
		height: 40px;
		border-radius: 10px;
		border-color: #DBDFB9;
	}
	
	#c{
		text-align: center;
	}
	
	#r{
		text-align: right;
	}

</style>

  <p>
   <h3>
       <a href="<?= Url::to(['/invoice/index']) ?>">
       	当月费用
       </a>
   </h3>
   </p>
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
			->andwhere(['in', 'realestate_id', "$id"])
			->andwhere(['in', 'invoice_status', '0']);
		  
		$amount = $inv->all(); //总费用
		  
		$inv01 = array_column($amount, 'amount'); //获取金额
		$sum = array_sum($inv01); //求和
		$sum01 = 0;
			  
		$invoice = $inv->andwhere(['in', 'year', date('Y')])
			->andwhere(['in', 'month', date('m')])
			->all();
	?>
    <table id="box3table">
         <?php foreach($invoice as $key => $in): $in = (object)$in ?>
            <tr>
                <td><div id="box302" width= "150px"><?= $in->description ?></div></td>
                <td align="right"><div id="box302"><?php echo $in->amount; $sum01 += $in->amount ?></div></td>
                <td align="center"><div id="box302"><?= $data[$in->status] ?></div></td>
            </tr>
        <?php endforeach; ?>
    </table>

<table id="box303">
  <tbody>
    <tr>
        <td id="c" width="50px">共:</td>
        <td width="70px" id="r"><l><?= 0-$sum01; ?></l></td>
        <td id="c" width="60px">往期:</td>
        <td id="r"><l><?= $sum01-$sum ?></l></td>
        <td id="c" width="40px">
             <?= Html::a('<span class="glyphicon glyphicon-credit-card"></span>','/invoice/view', ['class' => 'btn btn-info', 'title' => '立即缴费']) ?>
        </td>
    </tr>
  </tbody>
</table>
</div>

