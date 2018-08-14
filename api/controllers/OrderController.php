<?php
namespace api\controllers;

use common\models\Invoice;
use common\models\Order;
use common\models\Products;
use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class OrderController extends Controller
{
    //确认订单
    function actionCheck($order_id)
    {
        $result = Order::updateAll(['verify' => '1'], 'order_id = :id', [':id' => "$order_id"]);

        $result = Json::encode($result);
        return $result;
    }

    //确认订单
    function actionTrash($order_id, $id)
    {
        $status = ['3', '4', '5', '6', '8'];
        $model = Order::find()
            ->select('status')
            ->andwhere(['order_id' => $order_id])
            ->andWhere(['in', 'payment_gateway', $status])
            ->andWhere(['status' => '2'])
            ->column();

        if(!$model){
            return false;
        }

        $invoice = Products::find() //查找费项ID
        ->select('product_id as id')
            ->where(['order_id' => "$order_id"])
            ->asArray()
            ->all();

        $transaction = Yii::$app->db->beginTransaction(); //开始数据事务
        try{
            foreach($invoice as $in){ //循环数组并更新相关信息
                Invoice::updateAll(['order_id' => '', 'payment_time' => '', 'invoice_status' => '0'], 'invoice_id = :id', [':id' => $in['id']]);
            }
            $order = Order::updateAll(['status' => '100', 'invoice_id' => $id, 'payment_time' => time()], 'order_id = :o_id', [':o_id' => $order_id]);
            $transaction->commit();
        }catch(\Exception $e){
            print_r($e);
            $transaction->rollBack();
        }

        if($order)
        {
            return true;
        }
        return false;
    }

    public function actionCount($fromdate, $todate, $community) //查询当日订单数量
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $community = Json::decode($community); //将json数组转换为普通数组
        $time = strtotime(date('Y-m-d')); //当日时间戳
        $order = (new \yii\db\Query())
            ->select('count(order_basic.order_id) as count, sum(order_basic.order_amount) as amount')
            ->from('order_basic')
            ->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->andwhere(['between', 'order_basic.payment_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['!=', 'status', '100'])
            ->andWhere(['or like', 'order_relationship_address.address', $community])
            ->andWhere(['status' => '2'])
            ->one();

        $order = Json::encode($order); //数组转换
        return $order;
    }

    //查询订单总量
    function actionList($fromdate, $todate, $page, $community)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }
        $community = Json::decode($community);

        $time = strtotime(date('Y-m-d')); //当日时间戳 from_unixtime(payment_time,'Y-m-d H:i:s')payment_time
        $order = (new \yii\db\Query())
            ->select("order_basic.order_id as order_number, order_basic.order_amount, order_basic.status, from_unixtime(payment_time) as payment_time, order_basic.verify,
            order_basic.payment_gateway, order_basic.payment_number, order_basic.property, order_basic.description,
            order_relationship_address.address, order_relationship_address.mobile_phone as phone, order_relationship_address.name")
            ->from('order_basic')
            ->join('inner join' , 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->andWhere([ 'between', 'order_basic.create_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['!=', 'status', '100'])
            ->andWhere(['or like', 'order_relationship_address.address', $community])
            ->andWhere(['order_basic.order_type' => '1'])
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

    function actionOne($order_id) //订单详情列表
    {
        $order = Order::find()
            ->select('verify')
            ->where(['order_basic.order_id' => "$order_id"])
            ->one();

        $order = Json::encode($order); //数组转换

        return $order;
    }
}
