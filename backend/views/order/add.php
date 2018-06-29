<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\UserInvoice */

$this->title = '缴费预览';
$this->params[ 'breadcrumbs' ][] = [ 'label' => '订单列表', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

Modal::begin( [
	'id' => 'view-modal',
	'header' => '<h4 class="modal-title">支付方式</h4>',
	//'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>', ['pay', 'id' => $model['id']]
] );
$url = Url::toRoute( ['/order/add','c' => $m,'id' => $id,'address' => $address, 'c_id' => $c_id ] );

$vJs = <<<JS
    $('.view').on('click', function () {
        $.get('{$url}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs( $vJs );

Modal::end();
?>

<div class="user-order-pay">

	<style type="text/css">
		th{
			text-align:center;
		}
						
		table{
			text-align:center;
			margin:auto;
		}
		
		#div0{
			text-align: center;
			font-size: 24px;
			color: #FFFFFF;
			background: url(/image/timg.jpg);
			width: 116px;
			height: 54px;
			background-size: 116px 54px;
			border-radius: 30px;
			position: relative;
			top: 25px;
			margin: auto;
		}
		
		h{
			position: relative;
			top: 12px;
		}
		</style>
							
		<table width="768" border="1" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<th>序号</th>
					<th colspan="6">详情</th>
					<th>应收</th>
					<th>实收</th>
				</tr>
				<?php foreach($invoice as $k =>$i): $i = (object) $i?>
				<tr>
					<td width="7%"><?= $k+1; ?></td>
					<td width=""><?= $i->community; ?></td>
					<td width="8%"><?= $i->building ?></td>						
					<td><?= $i->name ?></td>						
					<td width="9%"><?= $i->year; ?>年</td>
					<td width="8%"><?= $i->month; ?>月</td>
					<td width="" align="left"><?= $i->description; ?></td>
					<td width="9%"><?= $i->amount; ?></td>
					<td width="9%"><?= $i->amount; ?></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="2">共：<?= $n; ?>条</td>
					<td align="right" colspan="4">活动优惠：<?= '0%';?></td>
					<td align="right">合计：</td>
					<td colspan="2"><?= $m; ?>元</td>
				</tr>
			</tbody>
		</table>
		
		<div id="div0"><h>
		<?= Html::a('GOing...', '#', [
						'data-toggle' => 'modal',
						'data-target' => '#view-modal', //模态窗ID
						'class' => 'view', //模态窗名称
					]) ?></h></div>
	</table>
</div>