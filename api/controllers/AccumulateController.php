<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/28
 * Time: 10:46
 */

namespace api\controllers;


use common\models\StoreAccumulate;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class AccumulateController extends Controller
{
    //用户积分
    function actionIndex($accunt_id)
    {
        $count = StoreAccumulate::find() //查询用户积分
            ->select('sum(amount) as amount, income')
            ->where(['status' => '1'])
            ->groupBy('income')
            ->asArray()
            ->all();

        if(!$count){
            return false;
        }
        $out = '0'; //设置默认值
        $income = '0';

        if(count($count) == '2'){ //如果存在积分消费的情况
            foreach ($count as $c){
                if($c['income'] == '1')
                {
                    $income = $c['amount'];
                }elseif ($c['income'] == '2'){
                    $out = $c['amount'];
                }
            }
        }else{ //仅有积分
            foreach ($count as $c){
                $income = $c['amount'];
            }
        }

        $total = $income - $out; //求积分差

        if($total > '0')
        {
            return $total;
        }

        return false;
    }

    //查看积分记录 concat(substr(community_news.content, 1,50), '.....')
    function actionLog($accunt_id)
    {
        $count = (new Query()) //查询用户积分记录
            ->select(["from_unixtime(order_basic.payment_time) as payment_time, concat(substr(order_basic.description, 1, 10), '……') as description,
            order_basic.order_id, store_accumulate.income"])
            ->from('store_accumulate')
            ->join('inner join', 'order_basic', 'store_accumulate.order_id = order_basic.order_id')
            ->where(['store_accumulate.account_id' => "$accunt_id", 'order_basic.status' => '2'])
            ->all();

        if($count){ //判断记录是否为空
            $count = Json::encode($count);
            return $count;
        }

        return false;
    }
}