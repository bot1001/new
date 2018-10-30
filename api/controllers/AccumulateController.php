<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/28
 * Time: 10:46
 */

namespace api\controllers;


use common\models\StoreAccumulate;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class AccumulateController extends Controller
{
    //用户积分
    function actionIndex($accunt_id)
    {
        $count = StoreAccumulate::find() //查询用户积分
            ->select('sum(amount) as amount')
            ->where(['status' => '1', 'account_id' => "$accunt_id"])
            ->asArray()
            ->one();

        if($count){ //如果
            $a = $count['amount'];
            if($a > 0)
            {
                return $a;
            }
        }

        return '0';
    }

    //查看积分记录 concat(substr(community_news.content, 1,50), '.....')
    function actionLog($accunt_id, $page)
    {
        $log = (new Query()) //查询用户积分记录
            ->select(["from_unixtime(order_basic.payment_time) as payment_time, concat(substr(order_basic.description, 1, 10), '……') as description,
            order_basic.order_id, store_accumulate.income"])
            ->from('store_accumulate')
            ->join('inner join', 'order_basic', 'store_accumulate.order_id = order_basic.order_id')
            ->where(['store_accumulate.account_id' => "$accunt_id", 'order_basic.status' => '2']);

        $count = $log->count(); //总记录数
        $p = '10';
        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $p]); //实例化分页并设置每页显示数量
        $count = $log->offset($pagination->offset) //按业获取数据
            ->limit($pagination->limit)
            ->all();

        if($count){ //判断记录是否为空
            $count = Json::encode($count);
            return $count;
        }

        return false;
    }
}