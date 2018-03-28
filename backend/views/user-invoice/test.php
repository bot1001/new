<?php

use app\ models\ UserInvoice;
use kartik\daterange\DateRangePicker;
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
        border-collapse:collapse;  
    }
	thead {
		font-weight: bold;
		font-size: 17px;
	}
	j {
		font-weight: bold;
		font-size: 15px;
	}
</style>

<?php

$this->title = '缴费统计';

?>
  <?php echo $this->render('_search', ['model' => $searchModel, 'comm' => $comm]); ?>
   	<div class="row">
         <div class=""> 
			<?php echo '起：'.date('Y-m-d',  $from); ?>
		
			<?php echo '止：'.date('Y-m-d',  $to); ?>
		</div>
	</div>

<?php

echo '<hr />';
foreach ($data as $key=>$value)
        {
            $d = $value->attributes;
			print_r($d);echo '<br />';
        }
//var_dump($data);
?>







