<?php

use yii\ helpers\ Html;
use yii\ helpers\ Url;
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
<div style="min-height: 500px; background: #ffffff; max-width: 1000px;margin: auto;border-radius:10px;">
    <br/>
    <div class="order-print">
	<span id='div1'>
        <style>
            .order-print{
                background: #ffffe5;
                max-width: 700px;
                margin: auto;
                border-radius: 5px;
            }

            h3{
                text-align: center;
                font-family: 仿宋;
                font-weight: 820;
                text-decoration: underline;
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
                width: 650px;
                font-family: 仿宋;
            }
            img{
                width: 75px;
                border-radius:20px;
            }
            #print{
                width: 100px;
                height: 80px;
                background: #fff9e5;
                border-radius: 15px;
            }
            str{
                font-weight: bolder;
            }
        </style>

        <h3><?= $comm['community'];  ?></h3>
			<br>
		<table border="0">
			<tr>
				<td align="left">房号:<str><?= $address ?></str></td>
				<td align="center">业主姓名：<str><?= $comm['n']?></str></td>
				<td align="center">订单号：<str><?= $order_id ?></str></td>
				<td align="right">收款方式：<str><?= $e[$order['payment_gateway']]; ?></str></td>
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
                foreach($dc as $key => $c)
                {
                    foreach ($invoice as $key => $value)
                    {
                        if ($value['description'] !== $c)
                        {
                            continue;
                        }else{
                            $des[] = $value;
                        }
                    }

                    $one = end($des); //第一条缴费订单

                    //获取绑定水费编码
                    $price = (new \yii\db\Query())
                        ->select('cost_name.price')
                        ->from('cost_relation')
                        ->join('inner join', 'cost_name', 'cost_name.cost_id = cost_relation.cost_id')
                        ->andwhere(['cost_relation.realestate_id' => $comm['id']])
                        ->andwhere(['in', 'cost_name.cost_name', $one['description'] ])
                        ->limit(20)
                        ->one();

                    //获取绑定费项价格
                    if(!empty($price))
                    {
                        $name = $price['price'];
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

                    $amount01 = array_sum(array_column($des,'invoice_amount')); //费项金额

                    echo ("<tr height=10>
	                          <td width = 6%>$k</td>
							  <td style='max-width: 17%; text-align: left;'>$c</td>
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
                            ->andwhere(['realestate_id' => $comm['id']])
                            ->andwhere(['<=', 'year', $wy])
                            ->andwhere(['<=', 'month', $wm])
                            ->orderBy('year DESC')
                            ->asArray()
                            ->one();
                        //获取最后一条水表读数
                        $read2 = WaterMeter::find()
                            ->select('readout')
                            ->andwhere(['realestate_id' => $comm['id']])
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
					            <td width = 10%>$one</td>
					            <td width = 10%>$two</td>
					    		<td width = 6%>$d </td>
					         ");
                    }else{
                        echo ("
						    <td width = 10%></td>
						    <td width = 10%></td>
							<td width = 6%>$count</td>
						");
                    }
                    echo (" 
	                        <td width = 7%>$name</td>
	                        <td width = 12%>$y/$h/1</td>
	                        <td width = 13%>$Y/$H/$day</td>
	                        <td style='width: 9%;text-align: right'>$amount01</td>
						  ");

                    echo "<td>".$m['note']."</td></tr>";

                    unset($des);// 释放数组
                    unset($yj);// 释放数组
                }
                ?>

                <tr>
					<td colspan="9" align="right">合计：  &nbsp;&nbsp;&nbsp;<?= $amount; ?></td>
					<td colspan="3"></td>
				</tr>
			</tbody>
		</table>
		<table border="0">
			<tr style="height: 5px;">
				<td></td>
			</tr>
			<tr>
				<td align="left" width = "200px">收款人：<str><?= $user_name; echo '('.Yii::$app->request->userIP.')'; ?></str></td>
				<td width = "200px">时间：<str><?= date('Y-m-d H:i:s', $order['payment_time']) ?></str></td>
				<td align="right" width = "256px">广西裕达集团物业服务有限公司(盖章)</td>
			</tr>
		</table>
	</span>
        <table id="print">
            <tr>
                <td>
                    <a href="javascript:printme()" rel="external nofollow" target="_self">
                        <img src='/image/print.png'>
                    </a>
                </td>
            </tr>
        </table>
    </div>
</div>
