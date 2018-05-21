<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\Status;
use yii\bootstrap\modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '生成费项';
$this->params['breadcrumbs'][] = ['label' => '缴费管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '费项预览';
?>
<div class="user-invoice-index">
  
	<style>
		th{
			text-align: center;
		}
		table thead tr td{
			text-align: center;
			font-size: 17px;
			font-weight: bolder;
		}
		#div1{
			width: 116px;
			height: 54px;
			text-align: center;
			font-size: 24px;
			background: url(/image/timg.jpg);
			background-size: 116px 54px;
			border-radius: 30px;
			position: relative;
			top: 10px;
			margin: auto;
			color: #FFFFFF;
		}
		h{
			position: relative;
			top: 12px;
		}
	</style>
   
  <table align="center" width="500" border="1">
	   <thead>
		   <tr>
			   <!-- <td>序号</td>-->
	           <td>小区</td>
               <td>楼宇</td>
               <td>单元</td>
               <td>房号</td>
               <td>年份</td>
               <td>月份</td>
               <td>名称</td>
               <td>金额</td>
			   <!-- <td>状态</td> -->
			   <td><center>备注</center></td>
		   </tr>
	   </thead>
  <tbody>
     <?php foreach($query as $a):  $a = (object)$a; ?>
       <tr>
          <!-- <th scope="col"></th> -->
         <th scope="col" width=""><?php echo $a->community_name; ?></th>
		   <th scope="col"><?php echo $a->building_name; ?></th>
		   <th scope="col"><?php echo $a->room_number; ?></th>
		   <th scope="col"><?php echo $a->room_name; ?></th>
		   <th scope="col"><?php $y = date('Y'); echo $y.'年'; ?></th>
		   <th scope="col"><?php $m = date('m'); echo $m.'月'; ?></th>
         <th scope="col"><?php echo $a->cost_name; ?></th>
		   <th scope="col" align="right"><?php $price = $a->price;
			   $acreage = $a->acreage;
			   if($a->cost_name == "物业费" || $a->cost_name == "空调运作费" || $a->cost_name == "水电周转金" ){
				   $p = $price*$acreage;
				   $price = number_format($p, 1);
				   echo $price;
			   }else{
				   echo $price;
			   }
			   ?></th>
         <!-- <th scope="col">&nbsp;</th> -->
         <th scope="col"></th>
       </tr>
     <?php endforeach; ?>
  </tbody>
</table>  
	
	<div id="div1">
		<a href="<?=Url::to(['user-invoice/add']) ?>"><h>提交</h></a>
	</div>

</div>
