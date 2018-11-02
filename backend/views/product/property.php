<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 14:55
 */

use kartik\grid\GridView;
use yii\helpers\Html;

$script = <<<SCRIPT

$(".trash").on("click", function () {
    if(confirm('您确定要删除吗？')){
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if(keys.length == 0){
            alert('选择不能为空！');
        }else{
            $.ajax({
                url: '/product/trash',
                data: {ids:keys},
                type: 'get',
                success: function (id) {
                    t = JSON.parse(id);
                    alert('成功删除：'+id+ ' 条');
                    window.location.reload();
                },
                error: function (){
                    alert('删除失败！');
                }
            })
        }     
    }
});

SCRIPT;
$this->registerJs( $script );

?>
<style>
    .one th, .one td{
        text-align: center;
     }
</style>

<div class="one">
    <?= GridView::widget([
            'dataProvider' => $data,
            'toolbar' => [],
            'options' => [ 'id' => 'grid' ],
            'layout' => '{items}',
            'columns' => //'kartik\grid\CheckboxColumn',
                [
                    ['class' => 'kartik\grid\CheckboxColumn',
                        'name' => 'id'],
                    [ 'attribute'=> 'price',
                        'label' => '价格'],

                    [ 'attribute'=> 'size',
                        'label' => '尺寸',
                        'width' => '70px'],

                    [ 'attribute'=> 'color',
                        'label' => '颜色',
                        'width' => '80px'],

                    [ 'attribute'=> 'image',
                        'format' => ['image', ['width' => '55px', 'height' => '25px']],
                        'label' => '缩略图',
                        'width' => '80px'],

                    [ 'attribute'=> 'quantity',
                    'label' => '数量',
                        'width' => '80px']
            ],
            'pjax' => true,
        ]);
    ?>
</div>