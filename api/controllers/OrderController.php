<?php
namespace api\controllers;

use common\models\Order;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class OrderController extends Controller
{
    public function actionCount() //查询当日订单数量
    {
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $order = Order::find()
            ->select('count(order_id) as count, sum(order_amount) as amount')
            ->andwhere(['>=', 'payment_time', $time])
            ->andWhere(['status' => '2'])
            ->asArray()
            ->one();

        $order = Json::encode($order); //数组转换
        return $order;
    }

    //查询订单总量
    function actionList()
    {
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $order = (new \yii\db\Query())
            ->select('order_basic.order_id as order_number, order_basic.order_amount, order_basic.status, order_relationship_address.address')
            ->from('order_basic')
            ->join('inner join' , 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->where(['>=', 'order_basic.create_time', $time])
            ->orderBy('order_basic.create_time DESC')
            ->all();

        $order = Json::encode($order);
        return $order;
    }
}
