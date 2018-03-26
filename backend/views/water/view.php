<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\WaterMeter;
use app\models\CommunityRealestate;
use app\models\CostRelation;
use app\models\CostName;

/* @var $this yii\web\View */
/* @var $model app\models\WaterMeter */

$this->params['breadcrumbs'][] = ['label' => 'Water Meters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-meter-view">

 <?php
	/*foreach($r_id as $id){
			//获取近两个月的费表读数
		    $water = WaterMeter::find()->select('year,month,readout')->limit(2)->where(['realestate_id' => $id])//->limit(1)
				->orderBy('property')->asArray()->all();
			
			//提取近两个月的费表读数
			$i = array_column($water,'readout');
			
			//计算差额
			$c= end($i)-reset($i);
			if($c <0){
				$c = 0;
			}
			
			//查找关联费项ID
			$c_id = CostRelation::find()->select('cost_id')->where(['realestate_id' => $id])->asArray()->one();
			//查找关联费项单价
			$price = CostName::find()->select('price,cost_name')->where(['cost_id' => $c_id['cost_id']])->asArray()->one();
			//合计金额
			$mount = $c*$price['price'];
			
			//查找生成水费费项的小区和楼宇
			$info = CommunityRealestate::find()->select('community_id,building_id')->asArray()->one();
			$community = $info['community_id'];//小区
			$building = $info['building_id']; //楼宇
			$realestate = $id['realestate_id']; // 房屋ID
			
			//获取年月
			$i = end($water);//后一月的读数信息
			$y = $i['year']; // 年
			$m = $i['month']; // 月
			$f = date( time() ); // 创建时间
			
			$d = $price['cost_name'];//$y.'年'.$m.'月份'.
		}*/
   
?>
<table border="1" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <th scope="col">序号</th>
      <th scope="col">小区</th>
      <th scope="col">楼宇</th>
      <th scope="col">房号</th>
      <th scope="col">年份</th>
      <th scope="col">月份</th>
      <th scope="col">名称</th>
      <th scope="col">金额</th>
    </tr>
    <?php foreach($r_id as $id ): ?>
	  <tr>
	  	<?php print_r($id); ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php //echo 'k'; ?>
	  </tr>
	  <tr>
	  	<?php echo 'k'; ?>
	  </tr>
	  <?php endforeach; ?>
  </tbody>
</table>

</div>
