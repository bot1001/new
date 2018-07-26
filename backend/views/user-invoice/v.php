<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '生成费项';
$this->params['breadcrumbs'][] = ['label' => '缴费管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '费项预览';
?>
<div class="user-invoice-index">
    <style>
        table{
            margin: auto;
        }
        table thead th{
            height: 30px;
            font-family: 宋体;
            #font-weight: 500;
            font-size: 25px;
            text-align: center;
        }
        table tbody td{
            height: 30px;
            text-align: center;
            font-size: 20px;
        }

        #div1{
            font-size: 25px;
            text-align: center;
            line-height: 60px;
            width: 116px;
            height: 54px;
            background: url(/image/timg.jpg);
            background-size:116px 54px;
            border-radius: 30px;
            margin-top: 2%;
            margin: auto;
        }

        a{
            color: aliceblue;
        }

        l{
            color: red;
        }

        table tr:hover{background-color: #dafdf3;}

        table tr:nth-child(odd){
            background: #efefef;
        }

        table{
            border-collapse:collapse;
            width: 80%;
        }
    </style>

    <table border="1">
        <thead>
        <tr>
            <th>序号</th>
            <th width="20%">小区</th>
            <th width="8%">楼宇</th>
            <th width = "8%">单元</th>
            <th width = "8%">房号</th>
            <th width = "9%">年份</th>
            <th width = "7%">月份</th>
            <th>名称</th>
            <th>金额/元</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
            <?php
            $count = 0; //计数
            $sum = 0; //求和
            $i = 1;
            $mark = 0;
            $first = $b['from'];//起始日期
            $from_day = date('d', strtotime($first)); //提取预交当天日期

            $d = date('Y-m', strtotime("-1 month", strtotime($b['from'])));
            for($i; $i <= $b['month']; $i++)
            {
                $date = date('Y-m', strtotime("+$i month", strtotime($d)));
            ?>

            <?php foreach($query as $key => $a):  $a = (object)$a; ?>
                <tr>
                    <td><?php $count ++; echo $count; ?></td>
                    <td><?php echo $a->community_name?></td>
                    <td><?php echo $a->building_name; ?></td>
                    <td><?php echo $a->room_number ?></td>
                    <td><?php echo $a->room_name; ?></td>

                    <td><?php
                        $time = strtotime($date); //将时间转换成时间戳
                        echo date('Y', $time).'年'; ?>
                    </td>

                    <td><?= date('m', $time).'月'; ?></td>
                    <td style="text-align: left"><?= $a->cost_name; ?></td>

                    <td style="text-align: right">
                        <?php
                        $price = $a->price;
                        $acreage = $a->acreage;
                        $collagen = '1'; //设置默认计算比例
                        $day = date("t",strtotime("$date")); //指定月天数

                        if($date == date('Y-m', strtotime($first))){ //判断当前循环是否为第一次循环
                            $days = $day - $from_day+ 1; //计算本月剩余天数
                            $collagen = round($days/$day, '2'); //计算剩余天数占比
                        }

                        if($a->formula == "1"){ //计算金额
                            $p = $price*$acreage*$collagen;
                            $price = round($p, 1);
                            echo $price;
                        }elseif($a->formula == "2"){
                            $p = $price*$day;
                            $price = round($p, 1);
                            echo $price;
                        }else{
                            echo $price;
                        }

                        if($a->inv == 0) //判断费固定费用
                        {
                            unset($query[$key]);
                        }
                        $sum += $price;
                        ?>
                    </td>
                    <td><?= $a->property; ?></td>
                </tr>
                <?php endforeach; ?>

                <?php  }  ?>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td align="center">
                <?= '起始日期：'.'<l>'.$first.'</l>'; ?>
            </td>

            <td align="center">
                <?= '共：'.'<l>'.$count.'</l>'.' 条'; ?>
            </td>

            <td>
                <?= '合计：'.'<l>'.$sum.'</l>'.' 元'; ?>
            </td>
        </tr>
    </table>

    <div id="div1">
        <a href="<?=Url::to(['user-invoice/one',
            'acreage' => $acreage,
            'b' => $b,
        ]) ?>">GOing...</a>
    </div>
</div>
