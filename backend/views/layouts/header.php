<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\TicketBasic;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\UserData;
use yii\helpers\ArrayHelper;

$session= $_SESSION['user'];
$a = $session['community']; //获取小区
$t = TicketBasic::getTicket();

$one = strtotime(date('Y-m-d')); // 本日时间戳
$two = date(time()); //当前时间戳
       //var_dump($a);exit;
    if(empty($a))
	{
		//获取小区投诉
		$ticket = $t->andwhere(['ticket_status' => 1])
	        ->asArray()
			->all();
		
		//获取小区名称
    	$c_name = CommunityBasic::find()
    	   ->select('community_id, community_name')
    	   ->asArray()
    	   ->all();
		//获取楼宇
    	$b_name = CommunityBuilding::find()
    		->select('building_id, building_name')
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
		//获取小区投诉
		$ticket = $t->andwhere(['ticket_basic.community_id' => $a])
			->andwhere(['ticket_status' => 1])
	        ->asArray()
			->all();
		
		//获取小区名称
    	$c_name = CommunityBasic::find()
    	    ->select('community_id, community_name')
    	    ->where(['community_id' => $a])
    	    ->asArray()
    	    ->all();
		//获取楼宇
    	$b_name = CommunityBuilding::find()
    		->select('building_name, building_id')
    		->where(['community_id' => $a])
    		->asArray()
    		->all();
		//计算当日注册量
		$query = (new \yii\db\Query())->select([
			    'user_data.reg_time',
			    'community_realestate.community_id'])
			->from ('user_data')
			->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_data.account_id')
			->join('inner join','community_realestate','community_realestate.realestate_id =user_relationship_realestate.realestate_id')
			->andwhere(['between', 'user_data.reg_time', strtotime(date('Y-m-d')),date(time())])
		    ->andwhere(['community_realestate.community_id' => $a]);
    }

	$user = $query->all();
    $today = $query->count();
    $community = ArrayHelper::map($c_name, 'community_id', 'community_name'); //重新组合小区
    $building = ArrayHelper::map($b_name, 'building_id', 'building_name'); //重新组合楼宇


/*foreach($ticket as $t){
	print_r($t);echo '<br />';
}*/

/* @var $this \yii\web\View */
/* @var $content string */
?>

<!-- 页面自动更新代码 -->
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
            <span class="sr-only">Toggle navigation</span>
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
                        <li class="header"><h4>你有<?php echo count($ticket); ?>条未处理的投诉</h4></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <!-- 提醒信息开始 -->
								<li>
                                                                            
                                        <?php
                                        foreach($ticket as $t)
										{	
											if(isset($t)){
											$c_id = $t['r']['community_id'];
                                            $b_id = $t['r']['building_id'];
										}else{
												$c_id = $b_id = '';
											}
                                             ?>
                                            
											<a href="<?php 
											     if(($c_id && $b_id) == ''){
											     	echo Url::to(['#']);
											     }else{
											     	echo Url::to(['/ticket/index',
											     							'community' => $community[$c_id],
											     							'building' => $building[$b_id]
											     							]);
											     }
											 ?>">
										    <?php 
											    if(($c_id && $b_id) == '')
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
                        <li class="footer"><a href="<?php echo Url::to(['/ticket/index','name' => '待接单']) ?>">查看全部</a></li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?php echo $today ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">本日注册量：<l><?php echo $today ?></l>个</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                      <?php
	                                     foreach($user as $u){
	                                     	$community_id = $u['community_id'];
	                                     	
	                                     	foreach($community as $key => $comm)
	                                     	{
	                                     		if($key == $community_id){
	                                     			$k[] = $key;
	                                     		}else{
	                                     			continue;
	                                     		}
	                                     		$c_c = count($k);
	                                     		unset($k);
	                                     	}
	                                     	?>
	                                 <a href="#">
	                                 <?php
	                                    echo '<i class="fa fa-users text-aqua"></i>'.$community[$community_id].'：'.'<l>'.$c_c.'个'.'</l>'; echo '<br />';
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
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Create a nice theme
                                            <small class="pull-right">40%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-green" style="width: 40%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">40% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Some task I need to do
                                            <small class="pull-right">60%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-red" style="width: 60%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Make beautiful transitions
                                            <small class="pull-right">80%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
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
                                绑定小区<?php echo $session['community'] ?>
                                <small>角色：<?php echo $session['role'] ?></small>
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
