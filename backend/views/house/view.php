<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HouseInfo */

$this->title = '业主信息';
$this->params['breadcrumbs'][] = ['label' => 'House Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-info-view">


    <p>
        <?php // Html::a('Update', ['update', 'id' => $model->house_id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a('Delete', ['delete', 'id' => $model->house_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>
		
    <?php 	
	echo '<table>
		';
	        foreach($model as $m)
	        {
	        	echo '<tr><td>';
	        	echo DetailView::widget([
                'model' => $m,
                'attributes' => [
					
                    ['attribute' => 'house_id',
	        		'label' => '序号'],
					
					['attribute' => 'community',
	        		'label' => '小区'],
					
                    ['attribute' => 'building',
	        		'label' => '楼宇'],
					
					['attribute' => 'number',
					 'value' => function($model){
					    return $model['number'].'单元';
				    },
	        		'label' => '单元'],
					
					['attribute' => 'room_name',
	        		'label' => '房号'],
					
                    ['attribute' => 'name',
	        		'label' => '姓名'],
					
                    ['attribute' => 'phone',
	        		'label' => '手机'],
					
                    ['attribute' => 'IDcard',
	        		'label' => '身份证'],
					
                    ['attribute' => 'update',
					 'value' => function($m){
					     return date('Y-m-d H:i:s', $m['update']);
				     },
	        		'label' => '更新日期'],
					
                    ['attribute' => 'status',
					 'value' => function($model){
					 $date = ['停用', '在用'];
					     return $date[$model['status']];
				     },
	        		'label' => '状态'],
					
                    ['attribute' => 'address',
	        		'label' => '地址'],
					
                    /*['attribute' => 'politics',
	        		'label' => ''],*/
					
                    ['attribute' => 'property',
	        		'label' => '备注'],
                ],
            ]);
		echo '</td></tr>';
		
	}
	echo '
	</table>';
	 ?>
	 
	 <div align="">
		<a href="<?php echo Url::to(['/house/index']) ?>" class="btn btn-info">返回用户信息列表</a>
	</div>

</div>
