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
   
  <table align="center" width="500" border="1">
	   <thead>
		   <tr>
			   <!-- <td>序号</td>-->
	           <td><center><h5><strong>小区</strong></h5></center></td>
               <td><center><h5><strong>楼宇</strong></h5></center></td>
               <td><center><h5><strong>单元</strong></h5></center></td>
               <td><center><h5><strong>房号</strong></h5></center></td>
               <td><center><h5><strong>年</strong></h5></center></td>
               <td><center><h5><strong>月</strong></h5></center></td>
               <td><center><h5><strong>名称</strong></h5></center></td>
               <td><center><h5><strong>金额</strong></h5></center></td>
			   <!-- <td>状态</td> -->
			   <td><center>备注</center></td>
		   </tr>
	   </thead>
  <tbody>
     <?php foreach($query as $a):  $a = (object)$a; ?>
       <tr>
          <!-- <th scope="col"></th> -->
         <th scope="col" width=""><?php echo $a->community_name?></th>
		   <th scope="col"><center><?php echo $a->building_name; ?></center></th>
		   <th scope="col"><center><?php echo $a->room_name ?></center></th>
		   <th scope="col"><center><?php echo $a->room_number; ?></center></th>
		   <th scope="col"><center><?php $y = date('Y'); echo $y.'年'; ?></center></th>
		   <th scope="col"><center><?php $m = date('m'); echo $m.'月'; ?></center></th>
         <th scope="col"><?php echo $a->cost_name; ?></th>
		   <th scope="col" align="right"><?php $price = $a->price;
			   $acreage = $a->acreage;
			   if($a->cost_name == "物业费"){
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
	
<table align="center" width="500" border="0">
  <tbody>
    <tr align="right">
      <td width="300"></td>
      <td width="100"></td>
		<td><h4><center><a href="<?=Url::to(['user-invoice/add']) ?>">提交</a></h4></center></td>
    </tr>
  </tbody>
</table>

</div>
