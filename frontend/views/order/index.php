<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use kartik\ grid\ GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缴费记录';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="order-index">

	<style>
		#center{
			text-align: center;
		}
		
		#right{
			text-align:right;
		}
		
		#order{
			width: 800px;
			margin: auto;
			position: relative;
			margin-top: 10px;
			background: #F5F5F5;
			border-radius: 15px;
		}
		
		#tbody{
			background: #F0F0F0;
			border-radius:15px;
		}
		
		#order_img{
			width: 50px;
			height: auto;
			border-radius:5px;
		}
		
		#time{
			width: 250px;
		}
		
		#img{
			height: 60px;
		}
	</style>
	<table id="order" border="0" cellspacing="0" cellpadding="0">
		<?php foreach($data as $d): $d = (object)$d ?>
		<tr id="tbody">
			<td id="time" colspan="2">下单时间：<?= date('Y-m-d H:i:s', $d->create_time) ?></td>
			<td colspan="2">缴费单号：<a href="<?= Url::to(['/invoice/index', 'order_id' => $d->order_id]) ?>"><?= $d->order_id ?></a></td>
			<td id="center">裕达物业</td>
			
			<td id="center" colspan="2"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $d->id], ['class' => 'btn btn-info']) ?></a></td>
		</tr>
        
        <tr id="img">
        	<td id="center"><img id="order_img" src="/image/logo.png" /></td>
        	<td id="center"><?= $d->address; ?></td>
        	<td id="center"><?= $d->name; ?></td>
        	<td id="center"><?= $d->description; ?></td>
        	<td id="right"><?= $d->amount; ?></td>
        	<td id="center" width="100px">
        	<?php
				if(!empty($d->gateway)){
					echo Html::a($status[$d->gateway], ['view', 'id' => $d->id], ['class' => 'btn btn-success']);
				}else{
					echo Html::a('<span class="glyphicon glyphicon-credit-card"></span>', ['view', 'id' => $d->id], ['class' => 'btn btn-success']); 
				}
				 
				?></td>
        </tr>
        
        <tr style="background: #FFFFFF">
        	<td colspan="6"><br /></td>
        </tr>
		<?php endforeach; ?>
	</table>
	
</div>