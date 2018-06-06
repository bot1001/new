<?php

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
	
	#center{
		text-align: center;
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
    	    <?php echo $area[$user['province_id']].'-'.$area[$user['city_id']].'-'.$area[$user['area_id']] ; ?>
    	</td>
    </tr>
    
    <tr>
    	<td id="center" colspan="5">房屋信息：</td>
    </tr>
    <?php foreach($house as $h): $h = (object)$h; ?>
    <?php if($h){ ?>
    <tr>
      <td id="right">房号：</td>
         <td id="center" colspan="4">
            <?= $h->community.'-'.$h->building.'-'.$h->number.'单元'.$h->room.' 号'; ?>
         </td>
    </tr>
   	<tr>
    	<td id="center" colspan="2">封顶时间：</td>
    	<td id="center" colspan="3"><?= date('Y-m-d H:i:s', $h->finish); ?></td>   	
    </tr>
    
    <tr>
    	<td id="center" colspan="2">交房时间：</td>
    	<td id="center" colspan="3"><?= date('Y-m-d H:i:s', $h->delivery); ?></td>   	
    </tr>
    
    <tr>
    	<td id="center" colspan="2">装修时间：</td>
    	<td id="center" colspan="3"><?= date('Y-m-d H:i:s', $h->decoration); ?></td>   	
    </tr>
    
    <tr>
    	<td id="center" colspan="2">房屋朝向：</td>
    	<td id="center" colspan="3"><?= $h->orientation; ?></td>   	
    </tr>
    
    <tr>
    	<td id="center" colspan="2">备注：</td>
    	<td id="center" colspan="3"><?= $h->property; ?></td>   	
    </tr>
    <?php }else{ }?>
    <?php endforeach; ?>
  </tbody>
</table>

   
</div>
