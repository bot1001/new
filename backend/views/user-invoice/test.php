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
  <?php echo $this->render('_search', ['model' => $searchModel, 'comm' => $comm,'c_name' => $c_name]); ?>
   	<div class="row" style="position: relative;left: 85%">
         <div class=""> 
			<?php echo '起：'.$from; ?>
		
			<?php echo '止：'.$to; ?>
		</div>
	</div>

<?php

echo '<hr />';
foreach ($data as $key=>$value)
        {
            $d[] = $value->attributes;
			
        }

//print_r($data);echo '<br />';
foreach($comm as $key => $community) //遍历小区
{
	if($data){ //判断是否存在缴费数据
		foreach($d as $keys => $ds) //遍历缴费信息
	    {
	    	//截取数据
	    	if($ds['community_id'] == $key)
	    	{
	    		$y[] = $ds;
	    	}else{
	    		continue;
	    	}
	    	print_r($y);echo '<hr />';
			unset($d[$keys]);
	    	unset($y);
	    }
		
	}
	
	//print_r($d);echo '<hr />';
}

?>







