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
	
	#div1{
		font-size: 20px;
		font-weight: 1000;
		background: #FFFFFF;
		text-align: right;
		border-radius: 5px;
		position: relative;
		top: 5px;
	}
	
	#div12{
		font-size: 15px;
		font-weight: 1000;
		color:rgba(0,0,0,0.7);;
		background: #FFFFFF;
		text-align: right;
		border-radius: 5px;
		position: relative;
		top: -5px;
	}
</style>

<?php $this->title = '缴费统计'; 

    $message = Yii::$app->getSession()->getFlash('fail');
	if($message == '0'){
		echo "<script>alert('选择小区不能为空或月份超过13个月，请重新选择！')</script>";
	}
?>

<?php echo $this->render('_search', ['model' => $searchModel, 
									   'comm' => $comm,
									   'c_name' => $c_name, 
									   'building' => $building,
									  ]); ?>
<div id="div12">
	<?php echo '起始时间：'.$from.'&nbsp&nbsp&nbsp&nbsp'.'截止时间：'.$to; ?>
</div>
	    	
<?php
if($data)//判断是否存在缴费数据
{ 	
	//拆分时间
	$f = explode('-', $from);
	$t = explode('-', $to);
	
	$d = UserInvoice::F();
		
	echo '<pre >';
	print_r($d);	
	
	exit;
	
	//初步过滤数组
	if(reset($f) != reset($t))
	{
		$d = UserInvoice::Summ($d, $f, $t);
	}
	
	$cost_name = array_column($d, 'description'); //提取缴费名称
	$cost_name = array_unique($cost_name); //费项名称去重复
	
	$amount = array_column($d, 'amount'); //提取总金额
	$amount = array_sum($amount); //总金额求和
	$amount = number_format($amount, 2)
	
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
		<?php 
	echo '<pre >';
	print_r($cost_name);
		?>		
   
    </tbody>
</table>

<div id="div1">
	<?= $amount ?>
</div>

<?php } ?>
