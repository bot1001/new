<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/11/4
 * Time: 12:34
 */

use kartik\grid\GridView;
use yii\helpers\Html;

?>

<div class="box_main">
    <div class="box_detail">
        <?php
        $dataProvider->query->where['type'] = '1';
        $gridview = [
            ['class' => 'kartik\grid\SerialColumn',
                'header' => '序号'],

            ['attribute' => 'detail',
                'value' => 'D',
            ],
        ];

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'panel' => ['type' => 'info', 'heading' => '物业消息'],
            'toolbar' => [],
            'layout' => '{page}',
            'hover' => true,
            'columns' => $gridview,
            'pjax' => true
        ]); ?>
    </div>

    <div class="information_menu">
        <?= Html::a('更多', ['index', 'type' => '1']) ?>
    </div>

</div>
