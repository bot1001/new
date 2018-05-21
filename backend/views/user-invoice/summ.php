<?php

use app\ models\ UserInvoice;
use kartik\ form\ ActiveForm;
use yii\ helpers\ Html;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

?>

<style type="text/css">
	div {
		margin: auto
	}
	td {
		height: 30px;
	}
	 table tr:hover{background-color: #dafdf3;}
	 table tr:nth-child(odd){  
        background: #efefef;  
    }  
	
    table{
		width: 100%;
        border-collapse:collapse;
    }
	thead {
		font-weight: bold;
		font-size: 15px;
		text-align: center;
	}
	j {
		font-weight: bold;
		font-size: 15px;
	}
	table tbody td{
		text-align: center;
	}
</style>

<?php

$this->title = '缴费统计';

?>
  <?php echo $this->render('_search', ['model' => $searchModel, 
									   'comm' => $comm,
									   'c_name' => $c_name, 
									   'building' => $building,
									  ]); ?>
<div align="right">
	<?php echo '起始时间：'.$from.'&nbsp&nbsp&nbsp&nbsp'.'截止时间：'.$to;; ?>
</div>
	    	
<?php
if($data)//判断是否存在缴费数据
{ 
    foreach ($data as $key=>$value)
    {
        $d[] = $value->attributes;
    }
	
//	    echo '<pre >';
//		print_r($d);exit;
	
	//提取缴费名称
	$cost_name = array_column($d, 'description');
	$cost_name = array_unique($cost_name);
	?>
<table border="1">
	<thead>
		<tr>
			<td>序号</td>
			<td>小区</td>
			<td>订单/条</td>
			<?php
			foreach($cost_name as $cost){
				echo '<td>';
				    echo $cost;			
			    echo '</td>';
			}			   
			?>
			<td>费项/条</td>
			<td>合计</td>
		</tr>
	</thead>
	<tbody>
		<tr>
    <?php
	$i = 0;
    foreach($comm as $key => $community) //遍历小区
    {
		foreach($d as $keys => $ds) //遍历缴费信息
	    {
	    	//截取数据
	    	if($ds['community_id'] == $key)
	    	{
	    		$y[] = $ds; //过滤收费信息
				unset($d[$keys]);
	    	}else{
	    		continue;
	    	}
		}

		//判断是否存在缴费数据
		if(isset($y))
		{
			$i++;
			$order = array_column($y, 'order_id'); //提取订单
			$order_unique = array_unique($order); //订单去重复
			$order_count = count($order_unique)-1; //订单条数计数
			
			$amount = array_column($y, 'invoice_amount'); //提取缴费金额
		    $sum = array_sum($amount); //计算合计金额
			$in_count = count($y);
			
		    echo '<tr>';
		    	   		
		    echo '<td>';
		        echo $i; //序号
		    echo '</td>';
			
		    echo '<td>';
			?>
			<a href="<?php //echo Url::to(['/user-invoice/index', 'community_id' => $key,]); ?>">
	            <?php
		            echo $community; //小区
		        ?>
			</a>
	    <?php	
		    echo '</td>';
			
			echo '<td>';
			    if($order_count !== 0){//订单数量
			    	echo $order_count;
			    }else{
			    	echo '';
			    }		        
		    echo '</td>';
			
			foreach($cost_name as $cost){
			    //循环遍历缴费数据
			    foreach($y as $keys => $ys)
			    {
			    	if($ys['description'] == "$cost")
			    	{
			    		$in[] = $ys;
			    		unset($y[$keys]);
			    	}else{
			    		continue;
			    	}
			    }
				
				if(isset($in)){
					//提取缴费金额列
				    $amount02 = array_column($in, 'invoice_amount');
				    $sum02 = array_sum($amount02);
				    echo '<td>';
			           echo $sum02;
			        echo '</td>';
				    unset($in);
				}else{
					echo '<td>';
			        echo '</td>';
				}
			}
			
			//输出费项条数
			echo '<td>';			
			    echo $in_count;
			echo '</td>';
			
			//输出合计金额
			echo '<td>';			
			    echo $sum;
			echo '</td>';
			
			unset($y);
			unset($in_count);
			unset($sum);
			unset($order_count);
		}
		?>
		    </tr>			
		<?php
	}
} ?>
        </tr>
    </tbody>
</table>