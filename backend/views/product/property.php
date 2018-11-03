<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 14:55
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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

<script>
    function test() {
        alert('你好');
    }
</script>

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
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'formOptions' => ['action' => ['product/property']],
                            'inputType' => kartik\editable\Editable::INPUT_TEXT,
                        ],
                        'label' => '价格'
                    ],

                    [ 'attribute'=> 'size',
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'formOptions' => ['action' => ['product/property']],
                            'inputType' => kartik\editable\Editable::INPUT_TEXT,
                        ],
                        'label' => '尺寸',
                        'width' => '70px'],

                    [ 'attribute'=> 'color',
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'formOptions' => ['action' => ['product/property']],
                            'inputType' => kartik\editable\Editable::INPUT_TEXT,
                        ],
                        'label' => '颜色',
                        'width' => '80px'],

                    [ 'attribute'=> 'image',
                        'format' => 'raw',
                        'value' => function($model, $key){
                            $url = $model->image;
                            return Html::a( "<img src='$url' style='width: 35px' alt = '更新'/>", '#',
                                [
                                    'class' => 'pay',
                                    'alt' => '更新',
                                    'style'=> 'color: red',
                                    'data-toggle' => 'modal',
                                    'data-url' => Url::toRoute(['img', 'id' => $model->id, 'image' => $url ,'type' => 'property']),
                                    'data-title' => '更新图片', //如果不设置子标题，默认使用大标题
                                    'data-target' => '#common-modal',
                                ]
                            );
                        },
                        'label' => '缩略图',
                        'width' => '80px'],

                    [ 'attribute'=> 'quantity',
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'formOptions' => ['action' => ['product/property']],
                            'inputType' => kartik\editable\Editable::INPUT_TEXT,
                        ],
                        'label' => '数量',
                        'width' => '80px']
            ],
            'pjax' => true,
        ]);
    ?>
</div>