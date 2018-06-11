<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Area;

?>

<style type="text/css">
	h3{
		margin: auto;

		text-align: center;
		position: relative;
	}
	
	#box1-1{
		height: auto;
		width: 340px;
		position:relative;
		margin-top:10px;
	}
	
	#right{
		text-align: right;
	}
	
	#box1-1 td{
		text-align: center;
	}
	
	#new{
		margin: auto;
		text-align: center;
		position: relative;
		margin-top: 8px;
	}
</style>

<div id="b_1">
	<h3>
       <a href="#">
       	用户信息
       </a>
   </h3>
   
   <?php
	    $user = $_SESSION['user'];
	    $house = $_SESSION['house'];
	    $area = Area::find()
	    	->select('area_name')
	    	->orwhere(['like', 'id', $user['province_id']])
	    	->orwhere(['like', 'area_parent_id', $user['province_id']])
	    	->orwhere(['like', 'area_parent_id', $user['city_id']])
	    	->indexBy('id')
	    	->column();
	
//	echo '<pre />';
//	print_r($area);exit;
	?>
  <table id="box1-1" border="1">
  <tbody>
    <tr>
      <td id="right">姓名：</td>
      <td id="center"><?= $user['user_name'] ?></td>
      <td id="right">手机号码：</td>
      <td id="center" colspan="2"><?= $user['mobile_phone'] ?></td>
    </tr>
    <tr>
    	<td id="right">昵称：</td>
    	<td id="center"><?= $user['nickname']; ?></td>
    	<td id="right">状态：</td>
    	<td id="center"><?php $status = ['1' => '正常', '2' => '删除', '3' => '锁定'];
	        echo $status[$user['status']]; ?></td>
    	<td id="center" rowspan="2"><img src="<?= $user['face_path'] ?>" height="50px" /></img?></td>
    </tr>
    
    <tr>
    	<td id="right">地址：</td>
    	<td id="center" colspan="3">
    	    <?php echo $area[$user['province_id'].'0000'].'-'.$area[$user['city_id']].'-'.$area[$user['area_id']] ; ?>
    	</td>
    </tr>    
    
    <tr>
    	<td colspan="5">
    	    房屋信息：
    	</td>
    </tr>
    <?php foreach($house as $k => $h): $h = (object)$h; ?>
    <?php if($h){ ?>
    <tr>
      <td id="right">房号：</td>
         <td colspan="4">
			 <a href="<?= Url::to(['/realestate/change', 'k' => $k]) ?>">
                 <?= $h->community.'-'.$h->building.'-'.$h->number.'单元'.$h->room.' 号'; ?>
             </a>
         </td>
    </tr>
   	<tr>
    	<td colspan="2">封顶时间：</td>
    	<td colspan="3"><?php
				 $date= date('Y', $h->finish);
				  if($date <1980){
					  echo '未设置';
				  }else{
					  echo date('Y-m-d', $h->finish);
				  }
			?></td>   	
    </tr>
    
    <tr>
    	<td colspan="2">交房时间：</td>
    	<td colspan="3"><?php
				 $date= date('Y', $h->finish);
				  if($date <1980){
					  echo '未设置';
				  }else{
					  echo date('Y-m-d', $h->delivery);
				  }
			?></td>   	
    </tr>
    
    <tr>
    	<td colspan="2">装修时间：</td>
    	<td colspan="3"><?php
				 $date= date('Y', $h->decoration);
				  if($date <1980){
					  echo '未设置';
				  }else{
					  echo date('Y-m-d', $h->delivery);
				  }
			?></td>   	
    </tr>
    
    <tr>
    	<td colspan="2">房屋朝向：</td>
    	<td colspan="3"><?= $h->orientation; ?></td>   	
    </tr>
    
    <tr>
    	<td colspan="2">备注：</td>
    	<td colspan="3"><?= $h->property; ?></td>   	
    </tr>
    <?php }else{
    	echo "<tr>";
        	echo "<td>";
            	echo '您暂无绑定的房屋';
        	echo "</td>";
    	echo "</tr>";
    }?>
    <?php endforeach; ?>
  </tbody>
</table>
<div id="new">
	<?= Html::a('<span class="glyphicon glyphicon-plus"></span>','create', ['class' => 'btn btn-info', 'title' => '添加房屋']) ?>
	<?= Html::a('<span class="glyphicon glyphicon-minus"></span>','#', ['class' => 'btn btn-warning', 'title' => '解绑房屋']) ?>
</div>
</div>
