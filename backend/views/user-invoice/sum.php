<?php

use app\models\UserInvoice;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;

?>

<style type="text/css">
	div {
		margin: auto
	}
	
	table tr:hover {
		background-color: #dafdf3;
	}
	
	table tr:nth-child(odd) {
		background: #FFFFFF;
	}
	
	table {
		width: 100%;
		border-collapse: collapse;
	}
	
	table tbody td,th {
		text-align: right;
		height: 30px;
	}
	
	thead {
		font-weight: bold;
		font-size: 15px;
		text-align: center;
	}
		
	th{
		text-align: center;
	}
	
	#left{
		text-align:left;
	}
	
	#center{
		text-align: center;
	}
	
	#div1 {
		font-size: 20px;
		font-weight: 1000;
		background: #FFFFFF;
		text-align: right;
		border-radius: 5px;
		font-weight: 700;
		position: relative;
		top: 5px;
	}
		
	#div12 {
		font-size: 15px;
		font-weight: 1000;
		color: rgba(0, 0, 0, 0.7);
		background: #FFFFFF;
		text-align: right;
		border-radius: 5px;
		position: relative;
		top: -5px;
	}
	
	#last{
		font-size: 20px;
		font-weight: 500;
	}
</style>

<?php
$this->title = '缴费统计';

$message = Yii::$app->getSession()->getFlash( 'fail' );
if ( $message == '0' ) {
	echo "<script>alert('选择小区不能为空或月份超过13个月，请重新选择！')</script>";
}
?>

<?= $this->render( '_search', [ 'model' => $searchModel,
	'comm' => $comm,
	'c_name' => $c_name,
	'building' => $building,
] );
?>
<div id="div12">
	<?= '起始时间：'.$from.'&nbsp&nbsp&nbsp&nbsp'.'截止时间：'.$to; ?>
</div>

<?php
if ( !empty($data) ) //判断是否存在缴费数据
{
//	print_r($data);exit;
	//拆分时间
	$f = explode( '-', $from );
	$t = explode( '-', $to );

	$cost_name = array_column( $data, 'description' ); //提取缴费名称
	$cost_name = array_unique( $cost_name ); //费项名称去重复

	$amount = array_column( $data, 'amount' ); //提取总金额
	$amount = array_sum( $amount ); //总金额求和
	$amount = number_format( $amount, 2 );	
	?>

	<table border="1">
		<thead>
			<tr>
				<th>序号</th>
				<th>小区</th>
				<?php
				foreach ( $cost_name as $kkk => $cost ) {
					echo '<th>';
					   $des[$kkk] = 0;
					   echo $cost;
					echo '</th>';
				}
				?>
				<th>费项/条</th>
				<th>合计</th>
			</tr>
		</thead>
		<tbody>
			<?php 
	        $n = 0; // 设置默认序号
	        $all_count = 0; //设置默认费项条数 
	        foreach($_SESSION['community_id'] as $key => $c) //遍历小区
			{ 
				foreach( $data as $k => $in) //遍历收费项目
				{
					if($in['community'] == $key)
					{
						$invoice[] = $in;
						unset($data[$k]);
					}
				}
				
				if(isset($invoice)){
					
				$a = array_column($invoice, 'amount');
				$a = array_sum($a);
				$count = array_column($invoice, 'invoice');
				$count = array_sum($count);
				$all_count += $count; //费项条数总和
				$n ++; //计数器
		?>
			<tr>
			    <td id="center"><?= $n ?></td>
				<td id="left">
				   <a href="<?php
			            if(empty($_GET['InvoiceSumSearch'] )){
			            	$search['community_id'] = '';
			            	$search['building_id'] = ''; 
			            	$search['description'] = '';
			            	$search['from'] = date('Y-m').' to '.date('Y-m');
			            	$search['payment_time'] = ''; 
			            	$search['invoice_status'] = ''; 
			            }else{
			            	$search =$_GET['InvoiceSumSearch'];
			            }
			            
			            echo Url::to(['summ', 'search' => $search, 'a' => $a, 'key' => $key]) ?>">
				       <?= $c; ?>
				   </a>
				</td>
				
				<?php foreach($cost_name as $cost_key => $name) //遍历收费名称
			    { 
			    	$ins = array_column($invoice, 'description');
			    	if(in_array($name, $ins))
			    	{
			    	    foreach($invoice as $keys =>  $i)
			    	    {				
			    	        if($i['description'] == $name) // 判断当前费项是否是当前遍历的收费名称
			    	        {
			    	            $des[$cost_key] += $i['amount'];?>
								<td>
							       <a href="<?php $search['description'] = $name; echo Url::to(['summ', 'search' => $search, 'a' => $i['amount'], 'key' => $key]) ?>">
							           <?= $i['amount']; ?>
							       </a>
			    	        	</td>
			    	      <?php }
			    	    }
			    	}else{
			    		echo '<td>';
			    		echo '</td>';
			    	}
			    }
				unset($invoice);
				unset($i);
				?>
				<td><?= $count ?></td>
				<td><?= $a ?></td>
			</tr>
			<?php } }?>
			<tr id="last">
			    <td colspan="2">合计：</td>
			    <?php foreach($des as $des01){ ?>
			        <td><?= $des01 ?></td>
			    <?php } ?>
			    <td colspan="2" style="text-align: center"><?= $all_count.' 条'; ?></td>
			</tr>
		</tbody>
	</table>
	<div id="div1">
	    <?= '总计：'.$amount ?>
    </div>
	<?php } ?>    