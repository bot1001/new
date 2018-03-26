<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserRelationshipRealestateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '关联房号';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-relationship-realestate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p align="right">
        <?= Html::a('New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'account_id',
            'realestate_id',

           ['class' => 'yii\grid\ActionColumn',
		   'template'=>'{update}'],
        ],
    ]); ?>
</div>
