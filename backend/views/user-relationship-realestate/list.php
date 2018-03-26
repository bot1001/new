<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserRelationshipRealestateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-relationship-realestate-index">

	<table class="table" width="500">
        <tbody>
            <tr>
                <th>序号</th> 
                <th>小区</th> 
                <th>楼宇</th>
                <th>单元</th>
                <th>房号</th>
                <th>姓名</th>
                <th>类型</th>
                <th>支付通道</th>
                <th>生成时间</th>
                <th>支付时间</th>  
                <th>交易号</th>  
                <th>合计</th>
                <th>状态</th> 
                <th>操作</th>     
            </tr> 
        <?php // print_r($sql); ?>
           <?php foreach($sql as $c): $c = (object)$c; ?>
            <tr>
				<th><?=$c->id;?></th>
				<th><?=$c->community_name;?></th>
				<th><?=$c->building_name;?></th>
				<th><?=$c->room_name;?></th>
				<th><?=$c->room_number;?></th>
				<th><?=$c->owners_name;?></th>
				<th><?php 
					if($c->order_type ==1):
					   echo "物业";
					else:
					   echo "商城";
					endif;
					?></th>
				<th><?php
					if($c->payment_gateway == 1):
					   echo "支付宝";
					elseif($c->payment_gateway == 2):
					   echo "微信";
					else:
					   echo "其他";
					endif;
					?></th>
				<th><?=$c->create_time;?></th>
                <th><?php echo $c->payment_time;?></th>
                <th><?php echo $c->payment_number;?></th>
                <th><?php echo $c->order_amount;?></th>
				<th><?php
					if($c->status == 1): 
                    echo "未支付";
                elseif($c->status == 2):
                    echo "已支付";
                elseif($c->status == 3):
                    echo "已取消";
				elseif($c->status == 4):
                    echo "送货中";
                else:
                    echo "已签收";
                endif;
					?></th>
                <th>操作</th>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
