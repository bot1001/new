<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<style type="text/css">
    .box2{
        height: 250px;
        width: 100%;
        overflow: auto;
    }
    #box2-3, .box2-4{
        display: flex;
    }
    #box2-3-1{
        width: 35%;
    }

    #box2-3-2{
        width: 45%;
    }

    #box2-3-3{
        width: 20%;
        text-align: center;
    }

	#box2-4-1 {
        width: 70%;
		position: relative;
	}

    #box2-4-2 {
        text-align: center;
        width: 30%;
	}
    .box2-4{
        display: flex;
        margin-bottom: 5px;
    }
</style>

<div id="b_2">
	<p>
		<h3> <a href="<?= Url::to(['/order/index']) ?>">缴费记录</a> </h3>
	</p>
	
	<?php if($order){ ?> 
        <div class="box2">
            <?php foreach($order as $or): $or = (object)$or ?>
                <div style="border-radius: 5px; background: #fff;">
                    <div id="box2-3">
                        <div id="box2-3-1"><l>	<?= $or->order_id; ?></l>	</div>
                        <div id="box2-3-2"><?= date('Y-m-d H:i:s', $or->payment_time); ?></div>
                        <div id="box2-3-3" style="width: 16%"><?php if(!empty($or->gateway)){
                                echo $data[$or->gateway];
                            }?>
                        </div>
                    </div>

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
                    <div class="box2-4">
                        <div id="box2-4-1"><a href="<?= Url::to(['/order/view', 'id' => $or->id, 'community' => $community_id['id']]) ?>"><?= $or->address; ?></a></div>
                        <div id="box2-4-2"><?= $or->amount; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
		<div id="new">
		    <?= Html::a('更多','/order/index', ['class' => 'btn btn-info', 'title' => '更多记录']) ?>
	    </div>
	<?php }else{
	          echo '<h1>'.'暂无缴费记录'.'</h1>';
          } ?>
</div>