<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\TicketBasic;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\UserData;
use yii\helpers\ArrayHelper;
use app\models\OrderBasic;
use app\models\SysRole;

$session= $_SESSION['user'];
$a = $session['community']; //获取小区
$t = TicketBasic::getTicket(); //调用获取投诉数据方法
$o = OrderBasic::getOr(); //调用获取订单数据方法

$one = strtotime(date('Y-m-d')); // 本日时间戳
$two = date(time()); //当前时间戳
$r_id = $session['role']; //用户角色编号
    
    if(empty($a))
	{
		//获取订单
		$or = $o ->andwhere(['between', 'payment_time', $one, $two])->limit(8);
			
		//获取小区投诉
		$ticket = $t->andwhere(['ticket_status' => 1])
	        ->asArray()
			->all();
		
		//计算当日注册量
		$query = (new \yii\db\Query())->select([
			    'user_data.reg_time',
			    'community_realestate.community_id'])
			->from ('user_data')
			->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_data.account_id')
			->join('inner join','community_realestate','community_realestate.realestate_id =user_relationship_realestate.realestate_id')
			->where(['between', 'user_data.reg_time', $one, $two]);
    }else{
		//获取关联小区名字
		$community_name = CommunityBasic::find()->select('community_name')
			->where(['in', 'community_id', $a])
			->asArray()
			->one();
		//获取小区投诉
		$ticket = $t->andwhere(['ticket_basic.community_id' => $a])
			->andwhere(['ticket_status' => 1])
	        ->asArray()
			->all();
		
	    //获取订单
		$or = $o ->andwhere(['between', 'order_basic.payment_time', $one, $two])
			->andwhere(['like', 'order_relationship_address.address', $community_name['community_name']]);
		
		//计算当日注册量
		$query = (new \yii\db\Query())->select([
			    'user_data.reg_time',
			    'community_realestate.community_id'])
			->from ('user_data')
			->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_data.account_id')
			->join('inner join','community_realestate','community_realestate.realestate_id =user_relationship_realestate.realestate_id')
			->andwhere(['between', 'user_data.reg_time', $one, $two])
		    ->andwhere(['community_realestate.community_id' => $a]);
    }

	$user = $query->all(); //获取注册数据
    $today = $query->count(); //计算注册量总数
    $o_count = $or->count(); //订单总量
    $order = $or->orderBy('payment_time DESC')->all(); // 当日订单数据

    $o_c = array_column($order, 'community_id'); //订单中的小区编号
    $u_c = array_column($user, 'community_id'); //注册中的小区编号
    $o_b = array_column($order, 'building_id'); //订单中的楼宇编号
    $t_c = array_column(array_column($ticket, 'r'), 'community_id'); //投诉列表总的小区编号
    $t_b = array_column(array_column($ticket, 'r'), 'building_id'); //投诉列表总的楼宇编号
    
    $b = [$a];
    $comm_id = array_merge($o_c,$t_c); //合并小区编号
    $comm_id = array_merge($comm_id,$b); //合小区编号
    $comm_id = array_merge($comm_id,$u_c); //合并和去重复小区编号

    $build_id = array_unique(array_merge($o_b,$t_b)); //合并和去重复小区编号

    //获取楼宇
    $b_name = CommunityBuilding::find()
    	->select('building_name, building_id')
    	->where(['in', 'building_id', $build_id])
    	->asArray()
    	->all();

    //获取小区名称
    $c_name = CommunityBasic::find()
       ->select('community_id, community_name')
	   ->where(['in', 'community_id',$comm_id])
       ->asArray()
       ->all();

    //角色
    $role = SysRole::find()->select('id, name')->asArray()->all();
    $r_name = ArrayHelper::map($role, 'id', 'name');

    $community = ArrayHelper::map($c_name, 'community_id', 'community_name'); //重新组合小区
    $building = ArrayHelper::map($b_name, 'building_id', 'building_name'); //重新组合楼宇

/* @var $this \yii\web\View */
/* @var $content string */
?>

<!-- 页面自动更新代码 每5分钟刷新一次页面-->
<meta http-equiv='refresh' content='300'/>

<style>
	l{
	    color: #FF0004; 
		font-weight: bold;
		font-size: 20px;
	}
</style>

<header class="main-header">
	
    <?= Html::a('<span class="logo-mini"><img src="/image/logo.png" class="img-circle" style="width:40px"></span><span class="logo-lg">' . /*Yii::$app->name*/
				'<img src="/image/logo.png" class="img-circle" style="width:40px">&nbsp'.'&nbsp裕家人' . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">隐藏左边导航栏按钮</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?php echo count($ticket); ?></span>
                    </a>
                    <ul class="dropdown-menu">
						<li class="header"><h4>未处理投诉量：<a href="<?php echo Url::to(['/ticket/index','name' => '待接单', 'c' => $a]) ?>"><l><?php echo count($ticket); ?> </l>例</a></h4></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <!-- 提醒信息开始 -->
								<li>
                                     <?php
                                     foreach($ticket as $t)
								    {
								    	if(isset($t['r'])){
								    	$c_id = $t['r']['community_id'];
                                             $b_id = $t['r']['building_id'];
								    }else{
								    	$c_id = $b_id = '';
								    }
                                         ?>
                                        
								   <a href="<?php 
								       if(empty($t['r'])){
								       	echo '';
								       }else{
								       	echo Url::to(['/ticket/index',
								       							'community' => $community[$c_id],
								       							'building' => $building[$b_id],
													            'c' => $a
								       							]);
								       }
								   ?>">
								   
								   <?php 
								        if(empty($t['r']))
								        {
									    	
								        	echo '房屋有误，请核查'.$t['r']['room_name'].' '.
								        	date('Y-m-d H',$t['create_time']);
								        }else{
								        	echo $community[$c_id].' '.
								        	$building[$b_id].' '.
								        	$t['r']['room_name'].' '.
								        	date('Y-m-d H',$t['create_time']);
								        }
                                    }
								        ?>
                                      </a>
                                </li>
                                <!-- 提醒信息结束 -->                                                                                            
                            </ul>
                        </li>
                        <li class="footer"><a href="<?php echo Url::to(['/ticket/index','name' => '待接单', 'c' => $a]) ?>">查看全部</a></li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?php echo $today ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><h4>本日注册量：<a href="<?php echo Url::to(['/user/index', 
																		'one' => $one, 
																		'two' => $two
																				 ]); ?>"><l><?php echo $today ?></l>个</a></h4></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                      <?php
                                      if($user){
										  //遍历小区
										  foreach(array_unique($u_c) as $key => $comm)
										 {
											 //遍历注册信息
	                                     	foreach($user as $keys => $u)
	                                     	{
												$community_id = $u['community_id'];
	                                     		if($comm == $community_id){
	                                     			$k[] = $u;
	                                     		}else{
	                                     			continue;
	                                     		}
												$c_c = count($k); //统计数量
											}	
											unset($k); //释放数组
	                                     	?>
	                                 <a href="<?php 
										  if($c_c > 0){
											  echo Url::to(['/user/index',
																  'name' => $community[$community_id],
																  'one' => $one,
																  'two' => $two
																 ]);
										  }
										   ?>">
	                                 <?php
										      if($c_c > 0){
										    	  echo '<i class="fa fa-users text-aqua"></i>'.$community[$comm].'：'.'<l>'.$c_c.'个'.'</l>'; echo '<br />';
										      }
											 
	                                      }
									  }
									?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="<?php echo Url::to(['/user/index', 
																		'one' => $one, 
																		'two' => $two
																	   ]); ?>">查看全部</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger"><?php echo $o_count; ?></span>
                    </a>
                    <ul class="dropdown-menu">
						<li class="header"><h4>当日订单数量：<a href="<?php echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]) ?>"><l><?php echo $o_count; ?> </l>条</a></h4></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                   <?php foreach($order as $o){?>
									<a href="<?php echo Url::to(['/user-invoice/index', 'order_id' => $o['order_id']]) ?>">	
								   <?php echo $o['address'].' '.'时间：'.date('H:i', $o['payment_time']).' '.'<l>'.$o['order_amount'].'</l>'; ?>
									
									</a>
                                  <?php } ?>
                                  
                                   <!-- Task item 
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">50%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 50%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>-->
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="<?php 
									 if(empty($a)){
										 echo Url::to(['/order/index', 'one' => $one, 'two' => $two]);
									 }else{
										 echo Url::to(['/user-invoice/index','one' => $one, 'two' => $two]);
									 }
									  ?>">查看全部</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo $session['name'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
								辖区：<l><?php if($a){
                                                       	echo $community[$a];
                                                       }else{
                                                       	echo '全部';
                                                       } ?></l>
                                <small>角色：<?php echo ($r_name[$r_id]); ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">粉丝</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">点击率</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">朋友</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                               <?= Html::a(
                                    '修改密码',
                                    ['/sysuser/change'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                                
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
