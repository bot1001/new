<?php

use yii\helpers\Url;
use app\models\Information;
use app\models\TicketBasic;

?>
     
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <!--<div class="user-panel">
            <div class="pull-left image">
                <img src="<?php //echo Url::to('@web/image/logo.png') ?>" class="img-circle" alt="User Image"/> 
            </div>
            <div class="pull-left info">
                <p><a href="<?php //Url::to(['index']) ?>"><?php //echo $_SESSION['user']['name']; ?></a></p>

                 <i class="fa fa-circle text-success"></i> Online
            </div>
        </div>-->

        <!-- 搜索框起 -->
        <form action="/order/index" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="order_id" class="form-control" placeholder="请输入……"/>
              <span class="input-group-btn">
                <button type='submit' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- 搜索框止 -->

        <?php echo dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    //['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => '小区管理',
					 //'icon' => 'file-code-o', 
					 'url' => ['#'],
					 'items' => [
                        	['label' => '小区','url' => ['/community-basic']],
                        	['label' => '楼宇','url' => ['/building']],
                        	['label' => '房屋','url' => ['/community-realestate']],
                        	['label' => '公告栏','url' => ['/news']],
                        	['label' => '投诉建议','url' => ['/ticket']],
                        ]
					],
	           
	                ['label' => '物业缴费',
					 //'icon' => 'file-code-o', 
					 'url' => ['#'],
					 'items' => [
                    	['label' => '缴费管理','url' => ['/user-invoice']],
                    	['label' => '费项设置','url' => ['/cost-name']],
                    	['label' => '费项关联','url' => ['/costrelation']],
                    	['label' => '订单列表','url' => ['/order']],
                    	['label' => '水电抄表','url' => ['/water']],
                    	['label' => '手机抄表','url' => ['/water/phone']],
                    ]
					],
	
                    ['label' => '用户管理', /*'icon' => 'file-code-o', */'url' => ['/user']],
                    ['label' => '系统消息', /*'icon' => 'file-code-o', */'url' => ['/information/index']],
                    ['label' => '退出', 'url' => ['site/logout']],
	
                    /*['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                ],
            ]
        );
		
		$c = $_SESSION['user']['community']; //用户关联小区
		$name = $_SESSION['user']['name']; //操作用户
		
		if($c)
		{
			$ticket = TicketBasic::find()->select('ticket_id, ticket_number as number, community_id as community, create_time, remind')
	            ->andwhere(['in', 'ticket_status', 1])
	            ->andwhere(['<', 'remind', 10])
				->andwhere(['in', 'community_id', $c])
	            ->asArray()
	            ->all();
			
            if($ticket)
			{
				$t_number = array_column($ticket, 'number'); //提取投诉单号
				$number = implode(',',$t_number); //拼接投诉单号
				$i_count = count($ticket); //计算未处理投诉、建议数量
				$detail = '您小区新增'.$i_count.'例投诉或建议，请务必安排相关人员及时处理！';
			    
				//检查投诉单号是否存在
				$information = Information::find()
					->select('ticket_number, times, remind_time')
					->where(['ticket_number' => $number])
					->asArray()
					->one();
				
				if($information)
				{
					$now = date(time()); //获取当前时间
					$time = $information['remind_time']; //提醒信息中的时间
					$second = $now-$time; //计算时间差
					
					if($second >= 1800)
					{
						$remind = $information['times'] += 1;
						
						Information::updateAll(['remind_time' => date(time()),
											'times' => $remind,
											'reading' => 0],
										    'ticket_number = :number',
										   [':number' => $information['ticket_number']]);
						
						foreach($ticket as $ts)
                        {	
	                        $model = new Information(); //实例化模型
	                        
	                        //更新投诉列表中的提醒次数
	                        TicketBasic::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);	            
	                    }
						echo "<script>alert('$detail')</script>";
					}
				}else{
					foreach($ticket as $ts)
                    {	
	                    $model = new Information(); //实例化模型
	                    
	                    $remind = $ts['remind']; //提醒次数
	                    $remind += 1; //提醒次数自动递加
	                    
	                    //更新投诉列表中的提醒次数
	                    TicketBasic::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);	            
	                }
			        
					//模型赋值
			        $model->community = $c;
	                $model->target = $name;
	                $model->detail = $detail;
					$model->times = $remind;
	                $model->reading = 0;
	                $model->ticket_number = $number;
	                $model->remind_time = date(time());
                    
                    $model->save(); //保存
					echo "<script>alert('$detail')</script>";
				}
			}
		}
		
		?>

    </section>

</aside>
