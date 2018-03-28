<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
use app\models\UserInvoice;
use app\models\CostName;
use app\models\CostRelation;
use app\models\WaterMeter;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = '打印';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<script>
	function printme() {
		document.body.innerHTML = document.getElementById( 'div1' ).innerHTML + '<br/>';
		window.print();
	}
</script>


<div class="user-order-pay">

	<span id='div1'>
		<style>
        	h3{
        		text-align: center;
        	}
           th{
           		text-align:center;
           	}
			
			tr{
				height:27px;
			}
        	table{
        		text-align: center;
        		margin:auto;
        		width: 800px;
        	}
        </style>
        
		<h3><?php echo $comm['community_name'];  ?></h3>
			
		<table border="0">
			<tr>
				<td align="left"><strong>房号:</strong>
					<?php echo $building['building_name'].'&nbsp'. $r_name['number']. '&nbsp'. $r_name['name']; ?>
				</td>
				<td align="center"><?php echo '业主姓名：'.$r_name['n']?></td>
				<td align="center"><?php echo '订单号：'.$order_id ?></td>
				<td align="right"><?php echo '收款方式：'.$e[$order['payment_gateway']]; ?></td>
			</tr>
			<tr style="height: 5px;">
				<td></td>
			</tr>
		</table>
		<table border="1" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<th>序号</th>
					<th>项目</th>
					<th>上期读数</th>
					<th>本期读数</th>
					<th>数量</th>
					<th>单价</th>
					<th colspan="2">起始日期</th>
					<th>金额</th>
					<th>备注</th>
				</tr>
				<?php
				
				$k = 0;
				foreach($dc as $key => $c){
									
					foreach ($invoice as $key => $value)
                    {
                        if ($value['description'] !== $c)
                        {
                            continue;
                        }else{
                            $des[] = $value;
                        }
                    }
					
					//获取绑定水费编码
					$c_id = CostRelation::find()->select('cost_id')->where(['realestate_id' => $r_name['id']])->limit(20)->asArray()->all();
	                //获取绑定费项价格
					if(!empty($c_id)){
						$cost = CostName::find()->select('price')->andwhere(['cost_name' => $c])->andwhere(['cost_id' => $c_id])->asArray()->one();
						if(empty($cost)){
							$name = $cost['price'];
						}else{
							$name = '';
						}
					}else{
						$name = '';
					}
										
	                $k ++;//记录遍历次数
					
					$m = reset($des); // 第一条
					$M = end($des); // 最后一条
					$y = $m['year']; // 最小年
					$h = $m['month']; //最小月
					$Y = $M['year']; // 最大年
					$H = $M['month']; //最大月
					$count = count($des); //统计数量
					$day = date("t",strtotime("$Y-$H"));
					
					//预交信息
					$ys = date('Y',$order['payment_time']);
					$ms = date('m',$order['payment_time']);
					
					foreach ($invoice as $key => $value)
                    {
                        if ($value['description'] === $c)
                        {
							if($value['year'] <= $ys)
							{
								if($value['year'] === $ys)
								{
									if($value['month'] > $ms)
								    {
								    	$yj[] = $value;
								    }else{
								    	continue;
								    }
								}else{
									continue;
								}
							}else{
								$yj[] = $value;
							}
                        }else{
                            continue;
                        }
                    }
					
					if(isset($yj)){
						$f = reset($yj); //第一条
					    $l = end($yj); //后一条
					    
					    $yy = $f['year']; //预交起始年
					    $ym = $f['month']; //预交起始月
					    
					    $YS = $l['year']; //预交末年
					    $YM = $l['month']; //预交末月
						$yc = count($yj); //预交数量统计
					}else{
						$yc = $yj = $yy = $ym = $YS = $YM = '';
						
					}
						
					$amount = array_sum(array_column($des,'invoice_amount')); //费项金额
					
					echo ("<tr height=10>
	                          <td width = 4%>$k</td>
							  <td width = 15% align = left>$c</td>
							  ");
					
					if($c == '水费'){
					    //获取费表读数
						$wm = $h-1; //月
						$wy = $y; //年
						if($wm == 0){ //判断月份是否为零
							$wm = 12;
							$wy = $y-1;
						}
						//获取第一条费表读数				
						$readout = WaterMeter::find()
					    	->select('readout')
					    	->andwhere(['realestate_id' => $r_name['id']])
							->andwhere(['<=', 'year', $wy])
							->andwhere(['<=', 'month', $wm])
					    	->orderBy('year DESC')
					    	->asArray()
					    	->one();
						//获取最后一条水表读数
						$read2 = WaterMeter::find()
					    	->select('readout')
					    	->andwhere(['realestate_id' => $r_name['id']])
							->andwhere(['year' => $Y])
							->andwhere(['month' => $H])
							->orderBy('year DESC')
							->asArray()
							->one();
						
					    if($readout){
					        $one = $readout['readout']; //上一个水表读数
							if($read2){
								$two = $read2['readout']; //最新水表读数
								$d = $two-$one; //水费差值
							}else{
								$d = $two = '';
							}   
						}else{
							$one = $two = $d = '';
						}
					    
					    echo ("
					            <td width = 9%>$one</td>
					            <td width = 9%>$two</td>
					    		<td width = 6%>$d </td>
					         ");
					}else{
						echo ("
						    <td width = 9%></td>
						    <td width = 9%></td>
							<td width = 6%>$count</td>
						");
					}
					echo (" 
	                        <td width = 5%>$name</td>
	                        <td width = 9%>$y/$h/1</td>
	                        <td width = 10%>$Y/$H/$day</td>
	                        <td width = 7% align = right>$amount</td>
						  ");
					if($yj && $c !== '水费'){
						echo ("<td>预收:$yy/$ym/-$YS/$YM 共:$yc 条</td></tr>");
					}else{
						echo ("<td></td></tr>");
					}
					
					unset($des);// 释放数组
					unset($yj);// 释放数组
			    }
				?>
								
				<tr>
					<td colspan="9" align="right">合计：  &nbsp;&nbsp;&nbsp;<?php echo $i_a; ?></td>
					<td colspan="3"></td>
				</tr>
			</tbody>
		</table>
		<table border="0">
			<tr style="height: 5px;">
				<td></td>
			</tr>
			<tr>
				<td align="left" width = "256px">收款人：<?php echo $user_name; echo '('.Yii::$app->request->userIP.')'; ?></td>
				<td width = "256px"><?php echo '时间：'.date('Y-m-d H:i:s', $order['payment_time']) ?></td>
				<td align="right" width = "256px">广西裕达集团物业服务有限公司(盖章)</td>
			</tr>
		</table>
	</span>
	<table>
		<tr>
			<td>
			<br />
				<a href="javascript:printme()" rel="external nofollow" target="_self"><image src='/image/print.png' width = '10%' height = '10%'></image></a>
			</td>
		</tr>
	</table>
</div>