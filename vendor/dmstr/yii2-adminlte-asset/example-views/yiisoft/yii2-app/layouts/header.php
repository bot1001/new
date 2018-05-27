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
use mdm\admin\components\Helper;

    $session= $_SESSION['user']['0'];
    $a = $_SESSION['community']; //获取小区
    $t = TicketBasic::getTicket(); //调用获取投诉数据方法
    $o = OrderBasic::getOr(); //调用获取订单数据方法
    
    $one = strtotime(date('Y-m-d')); // 本日时间戳
    $two = date(time()); //当前时间戳
    $r_id = $session['role']; //用户角色编号
    
    //获取关联小区名字
	$c_name = CommunityBasic::find()
		->select('community_name, community_id')
		->where(['in', 'community_id', $a])
		->asArray()
		->all();

    $name = array_column($c_name, 'community_name');

	//获取小区投诉
	$ticket = $t->andwhere(['in', 'ticket_basic.community_id', $a])
		->andwhere(['ticket_status' => 1])
	    ->asArray()
		->all();
	
	//获取订单
	$or = $o ->andwhere(['between', 'order_basic.payment_time', $one, $two])
		->andwhere(['or like', 'order_relationship_address.address', $name]);
	
	//计算当日注册量
	$query = \app\models\UserAccount::getUser($one, $two, $a);

	$user = $query->all(); //获取注册数据
    $today = $query->count(); //计算注册量总数
    $order = $or->orderBy('payment_time DESC')->all(); // 当日订单数据
    $o_count = count($order); //订单总量

    $o_c = array_column($order, 'community_id'); //订单中的小区编号
    $u_c = array_column($user, 'community_id'); //注册中的小区编号
    $o_b = array_column($order, 'building_id'); //订单中的楼宇编号
    $t_c = array_column(array_column($ticket, 'r'), 'community_id'); //投诉列表总的小区编号
    $t_b = array_column(array_column($ticket, 'r'), 'building_id'); //投诉列表总的楼宇编号
    
    $build_id = array_unique(array_merge($o_b,$t_b)); //合并和去重复小区编号

    //获取楼宇
    $b_name = CommunityBuilding::find()
    	->select('building_name, building_id')
    	->where(['in', 'building_id', $build_id])
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
	
	img{
		border-radius: 10px;
		width:40px;
	}
</style>

<header class="main-header">
	
    <?= Html::a('<img src="/image/logo.png">&nbsp'.'&nbsp裕家人' . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">隐藏左边导航栏按钮</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: 投诉信息 -->
                <li class="dropdown messages-menu">
                   <?php
	                   if (Helper::checkRoute('/ticket/index')) {
                               echo $this->render('ticket', ['ticket' => $ticket, 'a' => $a, 'community' => $community, 'building' => $building]);
                           }
	                    ?>
                </li>
                
                <!-- 注册信息 -->
                <li class="dropdown notifications-menu">
                   <?php
	                   if (Helper::checkRoute('/user/index')) {
                               echo $this->render('register', ['today' => $today, 'one' => $one, 'two' => $two, 'user' => $user, 'u_c' => $u_c, 'community' => $community,]);
                           }
	                    ?>
                </li>
                <!-- 订单信息 -->
                <li class="dropdown tasks-menu">
                   <?php
	                   if (Helper::checkRoute('/order/index')) {
                               echo $this->render('order', ['o_count' => $o_count, 'one' => $one, 'two' => $two, 'o' => $o, 'order' => $order]);
                           }
	                    ?>
                </li>
                
                <!-- 用户信息 -->
                <li class="dropdown user user-menu">
                   <?= $this->render('user', ['directoryAsset' => $directoryAsset, 'session' => $session, 'community' => $community, 'a' => $a, 'r_name' => $r_name, 'r_id' => $r_id]); ?>                    
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
