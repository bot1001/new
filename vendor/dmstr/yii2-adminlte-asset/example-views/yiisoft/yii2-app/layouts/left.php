<aside class="main-sidebar">

	<section class="sidebar">

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

		<?php
		use mdm\ admin\ components\ MenuHelper;

		$callback = function ( $menu ) {
			$data = json_decode( $menu[ 'data' ], true );
			$items = $menu[ 'children' ];
			$return = [
				'label' => $menu[ 'name' ],
				'url' => [ $menu[ 'route' ] ],
			];
			//处理我们的配置
			if ( $data ) {
				//visible
				isset( $data[ 'visible' ] ) && $return[ 'visible' ] = $data[ 'visible' ];
				//icon
				isset( $data[ 'icon' ] ) && $data[ 'icon' ] && $return[ 'icon' ] = $data[ 'icon' ];
				//other attribute e.g. class...
				$return[ 'options' ] = $data;
			}
			//没配置图标的显示默认图标
			( !isset( $return[ 'icon' ] ) || !$return[ 'icon' ] ) && $return[ 'icon' ] = 'fa fa-circle-o';
			$items && $return[ 'items' ] = $items;
			return $return;
		};
		?>

		<?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id,null,$callback),
        ]); ?>
        
        <?php
		$session = $_SESSION['user']; //从session中提交用户信息
		$community = $_SESSION['community']; //从session中提取小区
		
		$role = $session['0']['Role']; //用户角色
		$name = $_SESSION['user']['0']['name']; //用户名称
		
		if($role == '收银员')
		{
			$info = new app\models\Information; //统一实例化消息模型 
			$ticket = new app\models\TicketBasic; //统一实例化投诉模型 
			
			$t = $ticket::find()->select('ticket_id, ticket_number as number, community_id as community, create_time, remind')
	            ->andwhere(['ticket_status'=> '1'])
	            ->andwhere(['>', 'ticket_number', '128'])
	            ->andwhere(['<', 'remind', 10])
				->andwhere(['in', 'community_id', $community])
	            ->asArray()
	            ->all();
			
            if($t)
			{
				$t_number = array_column($t, 'number'); //提取投诉单号
				$number = implode(',',$t_number); //拼接投诉单号
				$i_count = count($t); //计算未处理投诉、建议数量
				$detail = '您小区新增'.$i_count.'例投诉或建议，请务必安排相关人员及时处理！';
				
				//检查投诉单号是否存在
				$information = $info::find()
					->select('ticket_number, times, remind_time')
					->where(['ticket_number' => $number])
					->asArray()
					->one();
				
				if($information)
				{
					$now = date(time()); //获取当前时间
					$time = $information['remind_time']; //提醒信息中的最后一次提醒时间
					$second = $now-$time; //计算时间差
					
					if($second >= 1800)
					{
						$remind = $information['times'] += 1;
						
						$info::updateAll(['remind_time' => date(time()),
											'times' => $remind,
											'reading' => 0],
										    'ticket_number = :number',
										   [':number' => $information['ticket_number']]);
						
						foreach($t as $ts)
                        {		                        
	                        //更新投诉列表中的提醒次数
	                        $ticket::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);	            
	                    }
						echo "<script>alert('$detail')</script>";
					}
				}else{
					foreach($t as $ts)
                    {	                    
	                    $remind = $ts['remind']; //提醒次数
	                    $remind += 1; //提醒次数自动递加
	                    
	                    //更新投诉列表中的提醒次数
	                    $ticket::updateAll(['remind' => $remind], 'ticket_id = :id', [':id' => $ts['ticket_id']]);	            
	                }
			        
					//模型赋值
			        $info->community = reset($community);
	                $info->target = $name;
	                $info->detail = $detail;
					$info->times = $remind;
	                $info->reading = 0;
	                $info->ticket_number = $number;
	                $info->remind_time = date(time());
                    
                    $info->save(); //保存
					echo "<script>alert('$detail')</script>";
				}
			}
		}
		?>
	</section>

</aside>