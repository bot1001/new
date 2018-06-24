<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\CommunityBasic */

$this->title = $model->community_id;
$this->params['breadcrumbs'][] = ['label' => 'Community Basics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="community-basic-view">
   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'community_id',
            'community_name',
            'community_logo',
	
		    ['attribute' => 'province.area_name', 'label' => '省份'],
		    ['attribute' => 'city.area_name', 'label' => '城市'],
		    ['attribute' => 'area.area_name', 'label' => '县区'],
            'community_address',
            'community_longitude',
            'community_latitude',
        ],
    ]) ?>
       
    <p align="center">
	       <?php
	   if(Helper::checkRoute('update'))
	   {
		   echo Html::a('更新', ['update', 'id' => $model->community_id], ['class' => 'btn btn-primary']);
	   } ?>
	   
	   <?php
	   if(Helper::checkRoute('update'))
	   {
		   echo Html::a('删除', ['delete', 'id' => $model->community_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);
	   } ?>
    </p>
</div>
