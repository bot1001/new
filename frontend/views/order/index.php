<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缴费记录';
$this->params[ 'breadcrumbs' ][] = $this->title;

Modal::begin([
    'id' => 'common-modal',
    'header' => '<h4 class="modal-title">支付方式</h4>',
    'footer' =>  '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
]);


$js = <<<JS
$(".modaldialog").click(function(){ 
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
$this->registerJs($js);

Modal::end();
?>
<div class="order-index">

	<style>
		#center{
			text-align: center;
		}
		
		#right{
			text-align:right;
		}
		
		#order{
			width: 800px;
			margin: auto;
			position: relative;
			margin-top: 10px;
			background: #F5F5F5;
			border-radius: 15px;
		}
		
		#tbody{
			background: #F0F0F0;
			border-radius:15px;
		}
		
		#order_img{
			width: 50px;
			height: auto;
			border-radius:5px;
		}
		
		#time{
			width: 250px;
		}
		
		#img{
			height: 60px;
		}
		
		#trash{
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
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
            padding: 12px 16px;
			z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
		
	</style>
	
	<script>
		function d(){
		    if(confirm('您确定要删除吗？')){
		    	$.ajax({
                    type: "GET",//方法类型
                    dataType: "json",//预期服务器返回的数据类型
                    url: "/order/delete" ,//url
                    data: $('#trash').serialize(), //获取值， name作为下标，value作为键值
                    success: function (result) {
                        if (result == 1) {
                            alert("删除成功！");
		    				location.reload();
                        };
                    },
                    error : function() {
                        alert("删除失败，请联系管理员！");
                    }
                });
            }
		}
	</script>
	
	<table id="order" border="0" cellspacing="0" cellpadding="0">
		<?php foreach($data as $d): $d = (object)$d ?>
		<tr id="tbody">
		<input id="trash" name="id" value="<?= $d->id ?>" />
			<td id="time" colspan="2">下单时间：<?= date('Y-m-d H:i:s', $d->create_time) ?></td>
			<td colspan="2">缴费单号：<a href="<?= Url::to(['/invoice/index', 'order_id' => $d->order_id]) ?>"><?= $d->order_id ?></a></td>
			<td id="center">裕达物业</td>
			
			<td id="center" colspan="2">
			    <div class="dropdown">
                    <span>
			             <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['class' => 'btn btn-warning', 'onclick' => "d()"]) ?>
			        </span>
                    <div class="dropdown-content">
                       删除
                    </div>
                </div>
			</td>
		</tr>
        
        <tr id="img">
        	<td id="center"><img id="order_img" src="/image/logo.png" /></td>
        	<td><a href="<?= Url::to(['view', 'id' => $d->id]) ?>"><?= $d->address; ?></a></td>
        	<td id="center"><?= $d->name; ?></td>
        	<td id="center"><?= mb_substr($d->description, 0, 15); ?></td>
        	<td id="right"><?= $d->amount; ?></td>
        	<td id="center" width="100px">
        	<?php $key = $d->id; ?>
                 <div class="dropdown">
                    <span>
        	        <?php
			        	if($d->status == '1'){
			        		echo Html::a('<span class="glyphicon glyphicon-credit-card"></span>', '#', [
                                            'class' => 'btn btn-success modaldialog',
                                            'data-toggle' => 'modal',
                                            'data-url' => Url::toRoute(['pay', 'id' => $key]),
                                            'data-title' => '支付方式', //r如果不设置子标题，默认使用大标题
                                            'data-target' => '#common-modal',
                            ]);?>
                    </span>
                    <div class="dropdown-content">
                       立即支付
                    </div>
              </div>
              
				<?php				
				}elseif($d->status == '2'){
					 echo Html::a('<span class="glyphicon glyphicon-print"></span>', ['print', 'id' => $d->order_id, 'amount' => $d->amount], ['class' => 'btn btn-info', 'title' => '打印']);
				}elseif($d->status == '3'){
					 echo Html::a('<span class="glyphicon glyphicon-remove"></span>', '#', ['class' => 'btn btn-warning', 'title' => '已取消']);
				}
				?></td>
        </tr>
        
        <tr style="background: #FFFFFF">
        	<td colspan="6"><br /></td>
        </tr>
		<?php endforeach; ?>
	</table>
	
	<div>
		<div id="page">
			<?php
	            echo yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
	        ?>
	    </div>
	</div>
	
</div>




