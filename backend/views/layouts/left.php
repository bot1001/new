<?php
use yii\helpers\Url;
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

        <?= dmstr\widgets\Menu::widget(
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
        ) ?>

    </section>

</aside>
