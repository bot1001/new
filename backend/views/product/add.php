<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/11/2
 * Time: 16:35
 */
?>
<style>
    #new{
        display: inline-flex;
    }
    #price,#size,#color, #quantity, .fileinput-button{
        width: 20%;
        border: solid 1px rgba(128, 128, 128, 0.57);
        border-radius: 3px;
        overflow: hidden;
    }
    .fileinput-button {
        position: relative;
        display: inline-block;
    }

    .fileinput-button input{
        position: absolute;
        left: 0px;
        top: 0px;
        opacity: 0;
        /*-ms-filter: 'alpha(opacity=0)';*/
    }
    .fileinput-button{
        color: red;
    }
</style>

<div id="new">
    <input type="" id="price" placeholder="价格"/>
    <input type="" id="size" placeholder="尺寸"/>
    <input type="" id="color" placeholder="颜色"/>
    <span class="fileinput-button">
        <span>缩略图</span>
        <input type="file" id="image" />
    </span>

    <input type="number" id="quantity" placeholder="库存"/>

</div>
