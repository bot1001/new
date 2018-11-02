<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 14:55
 */

use kartik\grid\GridView;
?>
<style>
    .one{
        display: inline-flex;
        text-align: left;
    }
    .color{
        width: ;
        background: rgba(0, 0, 0, 0.16);
    }
    .one th, .one td{
        text-align: center;
     }
</style>

<div class="one">
    <?= GridView::widget([
            'dataProvider' => $data,
            'toolbar' => [],
            'layout' => '{items}',
            'columns' =>
                [
                    [ 'attribute'=> 'price',
                        'label' => '价格'],

                    [ 'attribute'=> 'size',
                        'label' => '尺寸',
                        'width' => '70px'],

                    [ 'attribute'=> 'color',
                        'label' => '颜色',
                        'width' => '80px'],

                    [ 'attribute'=> 'image',
                        'label' => '缩略图',
                        'width' => '80px'],

                    [ 'attribute'=> 'quantity',
                    'label' => '数量',
                        'width' => '80px']
            ]
        ])
    ?>
</div>