<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '生成费项';
$this->params['breadcrumbs'][] = ['label' => '缴费管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '费项预览';
?>
<div class="user-invoice-index">
   
    <table align="center" width="768" border="0">
  <tbody>
    <tr>
		<th><h2><?php //print_r($c_name);exit;  ?></h2></th>
    </tr>
  </tbody>
</table>

  <table align="center" width="768" border="1">
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
               <td><center><h5><strong>金额/元</strong></h5></center></td>
			   <!-- <td>状态</td> -->
			   <td><center>备注</center></td>
		   </tr>
	   </thead>
  <tbody>
    
    <?php foreach($m as $ms): ?>
     <?php foreach($query as $a):  $a = (object)$a; ?>
       <tr>
          <!-- <th scope="col"></th> -->
         <th scope="col" width="170"><?php echo $a->community_name?></th>
		   <th scope="col"><center><?php echo $a->building_name; ?></center></th>
		   <th scope="col"><center><?php echo $a->room_name ?></center></th>
		   <th scope="col"><center><?php echo $a->room_number; ?></center></th>
		   <th scope="col"><center><?php echo $y; ?>年</center></th>
		   <th scope="col"><center><?php echo $ms; ?>月份</center></th>
         <th scope="col"><center><?php echo $a->cost_name; ?></center></th>
		   <th scope="col" align="right"><center><?php $price = $a->price;
			   $acreage = $a->acreage;
			   if($a->cost_name == "物业费"){
				   $p = $price*$acreage;
				   $price = number_format($p, 1);
				   echo $price;
			   }else{
				   echo $price;
			   }
			   ?></center></th>
         <!-- <th scope="col">&nbsp;</th> -->
         <th scope="col"></th>
       </tr>
     <?php endforeach; ?>
     <?php endforeach; ?>
  </tbody>
</table>  
	
<table align="center" width="768" border="0">
  <tbody>
    <tr align="center>
      <td width="500"></td>
      <td width="500"></td>
		<td><h4><a href="<?=Url::to(['user-invoice/one','a'=>$i]) ?>">GOing...</a></h4></td>
    </tr>
  </tbody>
</table>

</div>
