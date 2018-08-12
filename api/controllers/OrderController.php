<?php
namespace api\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class OrderController extends Controller
{
    public function actionCount($community) //查询当日订单数量
    {
        $community = Json::decode($community); //将json数组转换为普通数组
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $order = (new \yii\db\Query())
            ->select('count(order_basic.order_id) as count, sum(order_basic.order_amount) as amount')
            ->from('order_basic')
            ->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->andwhere(['>=', 'order_basic.payment_time', $time])
            ->andWhere(['!=', 'status', '100'])
            ->andWhere(['or like', 'order_relationship_address.address', $community])
            ->andWhere(['status' => '2'])
            ->all();

        $order = Json::encode($order); //数组转换
        return $order;
    }

    //查询订单总量
    function actionList($page)
    {
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $order = (new \yii\db\Query())
            ->select('order_basic.order_id as order_number, order_basic.order_amount, order_basic.status, order_relationship_address.address')
            ->from('order_basic')
            ->join('inner join' , 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->andwhere(['>=', 'order_basic.create_time', $time])
            ->andWhere(['!=', 'status', '100'])
            ->orderBy('order_basic.create_time DESC');

        $count = $order->count(); //求总数
        $p = '10';

        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => "$p"]); //实例化分页模型并设置每页获取数量

        $order = $order->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $order = Json::encode($order);//转换Json数据
        return $order;
    }
}
