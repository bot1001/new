<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缴费记录';
$this->params[ 'breadcrumbs' ][] = $this->title;

Modal::begin( [
	'id' => 'common-modal',
	'header' => '<h4 class="modal-title">支付方式</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
] );

$js = <<<JS
$(".pay").click(function(){ 
        aUrl = $(this).attr('data-url');
        aTitle = $(this).attr('data-title');
        console.log(aTitle);
        console.log(aUrl);
        
        $($(this).attr('data-target')+" .modal-title").text(aTitle);
        $($(this).attr('data-target')).modal("show")
             .find(".modal-body")
             .load(aUrl); 
        return false;
   }); 
JS;
$this->registerJs( $js );

Modal::end();
?>
<div class="order-index">

	<style>
		#center {
			text-align: center;
		}
		
		#right {
			text-align: right;
		}
		
		#order {
			width: 800px;
			margin: auto;
			position: relative;
			margin-top: 10px;
			background: #F5F5F5;
			border-radius: 15px;
		}
		
		#tbody {
			background: #F0F0F0;
			border-radius: 15px;
		}
		
		#order_img {
			width: 50px;
			height: auto;
			border-radius: 5px;
		}
		
		#time {
			width: 250px;
		}
		
		#img {
			height: 60px;
		}
		
		#trash {
			display: none;
		}
		
		.dropdown {
			position: relative;
			display: inline-block;
		}
		
		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 120px;
			font-size: 20px;
			border-radius: 10px;
			box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.5);
			padding: 12px 16px;
			z-index: 1;
		}
		
		.dropdown:hover .dropdown-content {
			display: block;
		}
		
		#page {
			width: 800px;
			margin: auto;
		}
	</style>

	<script>
		function d() {
			if ( confirm( '您确定要删除吗？' ) ) {
				$.ajax( {
					type: "GET", //方法类型
					dataType: "json", //预期服务器返回的数据类型
					url: "/order/delete", //url
					data: $( '#trash' ).serialize(), //获取值， name作为下标，value作为键值
					success: function ( result ) {
						if ( result == 1 ) {
							alert( "删除成功！" );
							location.reload();
						};
					},
					error: function () {
						alert( "删除失败，请联系管理员！" );
					}
				} );
			}
		}
	</script>

	<?php if($data){ ?>

	<table id="order" border="0" cellspacing="0" cellpadding="0">
		<?php foreach($data as $d): $d = (object)$d ?>
		<tr id="tbody">
			<input id="trash" name="id" value="<?= $d->id ?>"/>
			<td id="time" colspan="2">下单时间：
				<?= date('Y-m-d H:i:s', $d->create_time) ?>
			</td>
			<td colspan="2">缴费单号：<a href="<?= Url::to(['/invoice/index', 'order_id' => $d->order_id]) ?>"><?= $d->order_id ?></a>
			</td>
			<td id="center">裕达物业</td>

			<td id="center" colspan="2">
				<div class="dropdown">
					<span>
			             <?= Html::a('<span class="glyphicon glyphicon-trash"></span>
					', '#', ['class' => 'btn btn-warning', 'onclick' => "d()"]) ?>
					</span>
					<div class="dropdown-content">
						删除
					</div>
				</div>
			</td>
		</tr>

		<tr id="img">
			<td id="center"><img id="order_img" src="/image/logo.png"/>	</td>
            <?php
            //分割地址以获取小区
            $community = explode(' ', $d->address);
            if(count($community) == '1'){
                $community = explode('-', $d->address);
            }

            $name = reset($community); // 提取小区名称

            $community_id = \common\models\Community::find() //查找小区编码
                         ->select('community_id as id')
                         ->where(['community_name' => $name])
                         ->asArray()
                         ->one();

            if(empty($community_id)){ //如果小区不存在则默认为0
                $community_id['id'] = 0;
            }
            ?>

			<td><a href="<?= Url::to(['view', 'id' => $d->id, 'community' => $community_id['id']]) ?>"><?= $d->address; ?></a></td>
			<td id="center"><?= $d->name; ?></td>
			<td id="center"><?= mb_substr($d->description, 0, 15); ?></td>
			<td id="right"><?= $d->amount; ?>	</td>
			<td id="center" width="100px">
				<?php $key = $d->id; ?>
				<div class="dropdown">
					<span>
						<?php
						if ( $d->status == '1' ) {
							echo Html::a( '<span class="glyphicon glyphicon-credit-card"></span>', '#', [
								'class' => 'btn btn-success pay',
								'data-toggle' => 'modal',
								'data-url' => Url::toRoute( [ 'pay', 'id' => $key, 'community' => $community_id['id'] ] ),
								'data-title' => '支付方式', //如果不设置子标题，默认使用大标题
								'data-target' => '#common-modal',
							] );
							?>
					</span>
					<div class="dropdown-content">立即支付</div>
				</div>

				<?php
				} elseif ( $d->status == '2' ) {
					echo Html::a( '<span class="glyphicon glyphicon-print"></span>', [ 'print', 'id' => $d->order_id, 'amount' => $d->amount ], [ 'class' => 'btn btn-info', 'title' => '打印' ] );
				} elseif ( $d->status == '3' ) {
					echo Html::a( '<span class="glyphicon glyphicon-remove"></span>', '#', [ 'class' => 'btn btn-warning', 'title' => '已取消' ] );
				}
				?>
			</td>
		</tr>

		<tr style="background: #FFFFFF">
			<td colspan="6"><br/>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>

	<div id="page">
		<table width="800" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td>
						<?= yii\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
					</td>
					<td align="right">
						<?= '合计:'.$count.' 条'; ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php }else{
	          echo '<h1>'.'暂无缴费记录'.'</h1>';
          } ?>
	</div>
</div>