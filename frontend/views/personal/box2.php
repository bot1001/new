<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<style type="text/css">
	#box2-2 {
		font-size: 15px;
		font-weight: 700;
		overflow: auto;
		max-height: 230px;
		width: 100%;
	}
	
	#box2-2 tr td {
		text-align: center;
	}
	
	#box2td,
	#box2td0 {
		background: #F5F5F5;
		border-radius: 3px;
	}
	
	#box2td0 {
		position: relative;
		margin-bottom: 5px;
	}
</style>

<div id="b_2">
	<p>
		<h3>
       <a href="<?= Url::to(['/order/index']) ?>">
       	缴费记录
       </a>
   </h3>
	
	</p>
	
	<?php if($order){ ?> 
	<div style="height: 250px; width: 100%; overflow: auto">
		<table id="box2-2">
			<?php foreach($order as $or): $or = (object)$or ?>
			<tr>
				<td>
					<div id="box2td">
						<l>
							<?= $or->order_id; ?>
						</l>
					</div>
				</td>
				<td>
					<div id="box2td">
						<?= date('Y-m-d H:i:s', $or->payment_time); ?>
					</div>
				</td>
				<td>
					<div id="box2td">
						<?php if(!empty($or->gateway)){
               	        echo $data[$or->gateway];
                    }?>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="2">
                    <?php
                    //分割地址以获取小区
                    $community = explode(' ', $or->address);
                    if(count($community) == '1'){
                        $community = explode('-', $or->address);
                    }

                    $name = reset($community); // 提取小区名称

                    $community_id = \common\models\Community::find() //查找小区编码
                    ->select('community_id as id')
                        ->where(['community_name' => $name])
                        ->asArray()
                        ->one();

                    if(empty($community_id)){ //如果小区不存在则默认为0
                        $community_id['id'] = 0;
                    }
                    ?>
					<div id="box2td0"><a href="<?= Url::to(['/order/view', 'id' => $or->id, 'community' => $community_id['id']]) ?>"><?= $or->address; ?></a>
					</div>
				</td>
				</td>
				<td align="right">
					<div id="box2td0">
						<?= $or->amount; ?>
					</div>
				</td>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
		<div id="new">
		    <?= Html::a('更多','/order/index', ['class' => 'btn btn-info', 'title' => '更多记录']) ?>
	    </div>
	
	<?php }else{
	          echo '<h1>'.'暂无缴费记录'.'</h1>';
          } ?>
	
</div>