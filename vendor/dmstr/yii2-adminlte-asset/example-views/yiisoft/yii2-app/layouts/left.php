<aside class="main-sidebar">
	
	<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">
        function change() {
            $.ajax({
                type: "POST",//方法类型
                dataType: "json",//预期服务器返回的数据类型
                url: "/site/change" ,//url
                data: $('#form1').serialize(),
                success: function (result) {
                    if (result == 1) {
                        alert("切换成功！");
                    };
                },
                error : function() {
                    alert("服务器异常，请联系管理员！");
                }
            });
        }
    </script>
    
	<section class="sidebar">

		<!-- 切换小区起 -->
       <form id="form1" onsubmit="return false" action="##" method="post" class="sidebar-form">
            <div class="input-group">
              
            <select class="form-control" name="community_id"  placeholder="请输入……" />
            
                <option value="">请选择切换小区</option>
            <?php
				$community_id = $_SESSION['community_id'];
				foreach($community_id as $k_left => $community_left)
				{
				?>
					
		      	<option value="<?= $k_left ?>"><?= $community_left ?></option>
		      	<?php }	?>
			</select>
             
              <span class="input-group-btn">
                <button type='submit' id='search-btn' class="btn btn-flat" onclick="change()"><i class="fa fa-check"></i></button>
              </span>
            </div>
        </form>
       
        <!-- 切换小区止 -->

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

        <script>
            function remind() { //后台自动刷新投诉数据
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/auto/ticket', true);
                xhr.onload = function (res) {
                    var response = this.responseText;
                    if( response > '0'){
                        alert('您小区新增'+response+'例投诉或建议，请务必安排相关人员及时处理！')
                    }
                }
                xhr.send();
            }

            setInterval( function () {
                remind();
            }, 30000)
        </script>

	</section>
</aside>