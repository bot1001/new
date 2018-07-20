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
    $a = $_SESSION['community']; //获取用户关联小区编码
    $community = $_SESSION['community_id']; //用户关联小区
    $t = TicketBasic::getTicket(); //调用获取投诉数据方法
    $o = OrderBasic::getOr(); //调用获取订单数据方法
    
    $one = strtotime(date('Y-m-d')); // 本日时间戳
    $two = date(time()); //当前时间戳
    $r = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);

    foreach($r as $ro);
    $role = $ro->name;

	//获取小区投诉
	$ticket = $t->andwhere(['in', 'ticket_basic.community_id', $a])
		->andwhere(['ticket_status' => 1])
	    ->asArray()
		->all();

	//获取订单
	$or = $o ->andwhere(['between', 'order_basic.payment_time', $one, $two])
		->andwhere(['or like', 'order_relationship_address.address', $community]);
	
	//计算当日注册量
	$query = \app\models\UserAccount::getUser($one, $two, $a);

	$user = $query->all(); //获取注册数据
    $today = $query->count(); //计算注册量总数
    $order = $or->orderBy('payment_time DESC')->limit('20')->all(); // 当日订单数据
    $o_count = $or->count(); //订单总量

    //获取楼宇
    $building = CommunityBuilding::find()
    	->select('building_name, building_id')
    	->where(['in', 'community_id', $a])
    	->indexBy('building_id')
    	->column();

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
	
    <?= Html::a('<img src="/image/logo.png" style="border-radius: 10px;width:35px;">&nbsp'.'&nbsp裕家人' . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

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
                               echo $this->render('register', ['today' => $today, 'one' => $one, 'two' => $two, 'user' => $user, 'community' => $community,]);
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
                   <?= $this->render('user', ['directoryAsset' => $directoryAsset, 'session' => $session, 'community' => $community, 'a' => $a, 'role' => $role]); ?>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
