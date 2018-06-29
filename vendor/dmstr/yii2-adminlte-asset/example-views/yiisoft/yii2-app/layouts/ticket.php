<?php

use yii\helpers\Url;

?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?php echo count($ticket); ?></span>
                    </a>
                    <ul class="dropdown-menu">
						<li class="header"><h4>未处理投诉量：<a href="<?php echo Url::to(['/ticket/index','name' => '待接单', 'c' => $a]) ?>"><l><?php echo count($ticket); ?></l>例</a></h4></li>
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
								       							'community' => $c_id,
								       							'building' => $building[$b_id],
													            'c' => $a,
													            'ticket_status' => '1'
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
											
											$_community_id = $c_id;
											$_community = $community[$c_id];
											$_building = $building[$b_id];
											$_realestate = $t['r']['room_name'];
											$_time = $t['create_time'];
								        }
										$_ticket[] = ['community' => $_community, 'building' => $_building, 'room' => $_realestate, 'time' => $_time, 'community_id' => $_community_id];
										$t = count($ticket);
										//将投诉信息添加到session
										$_SESSION['_ticket'] = ['ticket'=> $_ticket, 'account' => $t];
										
										//释放数组
										unset($_community_id);
										unset($_community);
										unset($_building);
										unset($_realestate);
										unset($_time);
                                    }
									//释放数组
									unset($t);
									unset($_ticket);

								        ?>
                                      </a>
                                </li>
                                <!-- 提醒信息结束 -->                                                                                            
                            </ul>
                        </li>
                        <li class="footer"><a href="<?php echo Url::to(['/ticket/index','ticket_status' => '1', 'c' => $a]) ?>">查看全部</a></li>
                    </ul>