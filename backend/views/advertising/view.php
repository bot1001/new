<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Advertising */

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => 'Advertisings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .div{
        background: #fff9e5;
        border-radius: 10px;
        margin-left: 10px;
    }

    .excerpt{
        background: #ffffff;
        border-radius: 10px;
        margin-left: 10px;
        overflow-y: auto;
        max-height: 800px;
    }
		
	img{
		border-radius: 0px;
		width: 100%;
	}
	
	#td01{
        width: 150px;
		text-align: right;
	}
	
	#td02{
		text-align:center;
        width: 250px;
	}
	#tr01{
		height: 25px;
	}
	table{
		font-size: 20px;
		font-family: 宋体;
	}
	#ad_c{
		font-size: 16px;
		background: #7ABD77;
		width: 46%;
		position: relative;
		border-radius: 5px;
		right: -17px;
		margin-bottom: 2px;
	}
</style>


<div class="advertising-view">
	<div class="row">
		<div class="col-lg-3 div">
			<div>
				<h1><?= Html::encode('信息栏') ?></h1>
			</div>
			
			<table border="1" cellspacing="0" cellpadding="0">
              <tbody>
                  <tr id="tr01">
                      <td id="td01">标题：</td><td id="td02"><?= $model['title'] ?></td>
                  </tr>
                     
                  <tr id="tr01">
                      <td id="td01">类型：</td><td id="td02"><?= $type ?></td>
                  </tr>
                  
				  <tr id="tr01">
				      <td id="td01">发布平台：</td><td id="td02"><?= $model['value'] ?></td>
				  </tr>
				  
				  <tr id="tr01">
				      <td id="td01">位置：</td><td id="td02"><?= $location ?></td>
				  </tr>
				  
				  <tr id="tr01">
				      <td id="td01">创建时间：</td><td id="td02"><?= $model['create_time'] ?></td>
				  </tr>

                  <tr id="tr01">
                      <td id="td01">截至时间：</td><td id="td02"><?= $model['end_time'] ?></td>
				  </tr>
				  
				  <tr id="tr01">
				      <td id="td01">顺序：</td><td id="td02"><?= $model['sort'] ?></td>
				  </tr>
			      
			      <tr id="tr01"><td colspan="2">可见小区：</td></tr>

			      <tr>
				      <td colspan="2">
				          <div class="row">
				      	     <?php foreach($community as $comm){ ?>
						       	<div id="ad_c" class="col-lg-5">
						       	    <?= $comm; ?>
						         </div>
						     <?php } ?>
						  </div>
				      </td>
				  </tr>
				  
				  <tr id="tr01">
				      <td id="td01">状态：</td><td id="td02"><?= $status ?></td>
				  </tr>
				  
				  <tr id="tr01">
                     <td id="td01">备注：</td><td id="td02"><?= $model['property'] ?></td>
                  </tr>
              </tbody>
            </table>
            <p>
			    <div align="center"><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['id']], ['class' => 'btn btn-primary']) ?>
	            	 <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['id']], [
                        'class' => 'btn btn-danger', 
                        'data' => [ 
                            'confirm' => '您确定要删除吗?',
                            'method' => 'post', 
                        ], 
                    ]) ?> 
                </div>
			</p>
	    </div>
	
		<div class="col-lg-6 excerpt">
	    	<?= $model['excerpt'] ?>
	    </div>
	</div>
</div>
