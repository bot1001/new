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
  <style>
	  table thead th{
		  height: 30px;
		  font-family: 宋体;
		  #font-weight: 500;
		  font-size: 25px;
		  text-align: center;
	  }
	  table tbody td{
		  height: 30px;
		  text-align: center;
		  font-size: 20px;
	  }
	  table{
		  width: 768px;
	  }
	  #div1{
		  font-size: 25px;
		  text-align: center;
		  line-height: 60px;
		  width: 116px;
		  height: 54px;
		  background: url(/image/timg.jpg);
		  background-size:116px 54px;
		  border-radius: 30px;
		  margin-top: 2%;
		  margin-left: 300px;
	  }
	  
	  a{
		  color: aliceblue;
	  }
	  
	  l{
		  color: red;
	  }
	  
	  table tr:hover{background-color: #dafdf3;}
	  
	  table tr:nth-child(odd){  
        background: #efefef;  
    }  
	
    table{ 
        border-collapse:collapse;  
    }
  </style>
   
  <table border="1">
	   <thead>
		   <tr>
	           <th>小区</th>
               <th>楼宇</th>
               <th>单元</th>
               <th>房号</th>
               <th>年</th>
               <th>月</th>
               <th>名称</th>
               <th>金额/元</th>
			   <th>备注</th>
		   </tr>
	   </thead>
       <tbody>
         <tr>
         	<?php
			  $count = 0; //计数
			  $sum = 0; //求和
	          $i = 1;
	          $d = date('Y-m', strtotime("-1 month", strtotime($b['from'])));
	          for($i; $i <= $b['month']; $i++)
	          { 
	      	  
	      	  $date = date('Y-m', strtotime("+$i month", strtotime($d)));
	        ?>
	        
	        <?php foreach($query as $key => $a):  $a = (object)$a; ?>
             <tr>
                <td  width="170"><?php echo $a->community_name?></td>
	      	    <td><?php echo $a->building_name; ?></td>
	      	    <td><?php echo $a->room_name ?></td>
	      	    <td><?php echo $a->room_number; ?></td>
	      	  
                <td> <?php 
	      	  $time = explode('-', $date);
	      	  echo reset($time).'年'; ?>
                </td>
                
                <td><?php echo end($time).'月'; ?></td>
                
                <td style="text-align: left"><?php echo $a->cost_name; ?></td>
                
	      	  <td style="text-align: right"><?php 
	      	  
	      	       $price = $a->price;
	      		      $acreage = $a->acreage;
	      		      if($a->cost_name == "物业费"){
	      		   	   $p = $price*$acreage;
	      		   	   $price = number_format($p, 1);
	      		   	   echo $price;
	      		      }else{
	      		   	   echo $price;
	      		      }
				     if($a->inv == 0)
				     {
				   	  unset($query[$key]);
				     }
				    
				     $sum += $price;
				     $count ++;
	      		   ?>
	      		  </td>
	      	  <td></td>
             </tr>
           <?php endforeach; ?>
           
           <?php
	      	}	  
	        ?>
         	</td>
         </tr>
    
	  </tbody>
	</table>
	
	<table>
		<tr>
			<td align="center">
				<?php echo '共：'.'<l>'.$count.'</l>'.' 条'; ?>
			</td>
			
			<td>
				<?php echo '合计：'.'<l>'.$sum.'</l>'.' 元'; ?>
			</td>
		</tr>
	</table>
	
	<div id="div1">
		<a href="<?=Url::to(['user-invoice/one',
							 'acreage' => $acreage,
							         'b' => $b,
							]) ?>">GOing...</a>
	</div>
</div>
