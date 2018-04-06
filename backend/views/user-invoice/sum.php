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
	 table tr:hover{background-color: #FFFFFF;}
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

$form = ActiveForm::begin( [
	'action' => [ 'search' ],
	'type' => ActiveForm::TYPE_INLINE,
	'method' => 'post',
] );
?>

	<div class="raw">
	    <div class="col-lg-2">
		    <?= $form->field($model,'community_id')->dropDownList($c,['prompt' => '请选择……', 'id' => 'c']) ?>
		</div>
	    <div class="col-lg-1">
		    <?= $form->field($model, 'building_id')->widget(DepDrop::classname(), [
                   'type' => DepDrop::TYPE_SELECT2,
                   'options'=>['id'=>'building'],
	               'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                   'pluginOptions'=>[
                       'depends'=>['c'],
                       'placeholder'=>'请选择...',
                       'url'=>Url::to(['/costrelation/b'])
                   ]
               ]); ?>
		</div>
	    <div class="col-lg-2">
		    <?= $form->field($model,'to') ?>
		</div>
	    <div class="col-lg-1">
		    <?= $form->field($model,'a') ?>
		</div>
		<div class="col-lg-4">
		    <?php
		         echo $form->field( $model, 'from',  [
                        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
                        'options'=>['class'=>'drp-container form-group']
                    ])->widget(DateRangePicker::classname(), [
                        'useWithAddon'=>true
                    ]);
		    ?>
		    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
		</div>
		
	</div>	

	<?php ActiveForm::end(); ?>
	
<table width="250" border="0" align="right">	
	<tr>
		<td>
			<?php echo '起：'.date('Y-m-d',  $from); ?>
		</td>
		<td align="right">
			<?php echo '止：'.date('Y-m-d',  $to); ?>
		</td>
	</tr>
</table>


<table width="100%" border="1">
	<thead>
		<tr align="center">
			<td>序号</td>
			<td>小区</td>
			<td>订单/条</td>
			<?php
			if(!empty($de))
			{
				foreach ( $de as $k => $ts ) 
			    {
			    	if(isset($ts))
			    	{
			    		echo '<td>';
			    	        echo $ts;
			    	    echo '</td>';
			    	}
			    }
			}
			
			?>
			<td>费项/条</td>
			<td>合计/元</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$y = 0;
		foreach ( $community as $key => $com ) 
		{
			$y ++;
			if ( $sum ) 
			{
				
				$c_id = $com[ 'community_id' ]; //小区代码
				$c_name = $com[ 'community_name' ]; //小区名称
				
				//数组过滤，仅保留已缴费数据
				foreach ( $sum as $k => $value ) 
				{
					if ( empty($value[ 'order_id' ])) 
					{
						continue;
					} else {
						if($value['community_id'] === $c_id)
						{
							$su[] = $value;
						}else{
							continue;
						}
					}
				}
				//统计订单条数
				if ( empty( $su ) ) {
					$m = '0';
				} else {
					$o = array_column( $su, 'order_id' ); //获取订单列
					$or = array_unique( $o ); //去重复
					$m = count( $or ); //输出列
				}
				//统计缴费费项费项
				if ( empty( $su ) ) {
					$c = '';
				} else {
					$c = count( $su );
				}
				//统计总金额
				if ( empty( $su ) ) {
					$u = '';
					$s = '';
				} else {
					$u = array_column( $su, 'invoice_amount' );
					$s = array_sum( $u );
				}
			} else {
				$k = '';
				$c_name = '';
				$m = '';
				$c = '';
				$s = '';
				//$ = '';
			}
			echo '<tr>';
			    echo '<td align = center>';
			        echo $y;
			    echo '</td>';
    
			    echo '<td align = center>';
			        echo $c_name;
			    echo '</td>';
			
			    echo '<td align = center>';
			        echo $m;
			    echo '</td>';
			
			if(!empty($de))
			{
				foreach ( $de as $ke => $ts ) 
				{
					if(empty($su))
					{
						echo '<td>';
						    $s2 = '';
						    echo $s2;
						echo '</td>';
					}else{
						//过滤查询数据
						foreach($su as $sm)
					    {
					    	if($sm['description'] === $ts)
					        {
					        	$n[] = $sm;
					        }else{
					        	continue;
					        }
					        
						    //计算合计金额
					        $u2 = array_column($n,'invoice_amount');
					        $s2 = array_sum($u2);
					    }
						unset($n); //释放数组
			            //输出
			    	    echo '<td align=right>';
			    	        echo $s2;
			    	    echo '</td>';
					}
			    }
			}
			    echo '<td align = center>';
			        echo $c;
			    echo '</td>';
    
			    echo '<td align = right>';
			    echo $s;
			    echo '</td>';
			echo '</tr>';

			/*echo '<tr>';
			    echo '<td colspan = 3 align = center>';
			    //echo '合计：'.$in;
			    echo '</td>';
    
			echo '</tr>';*/
			unset( $su ); //释放数组
		}
		?>
	</tbody>
</table>
<table border="0" width="100%" align="right">
	<tbody>
		<tr>
		<br />			
			</td>
			<td align="right">
				<j>合计：<?php echo $in; ?></j>
			</td>
		</tr>
	</tbody>
</table>