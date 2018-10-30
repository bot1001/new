<?php
namespace api\controllers;

use common\models\Api;
use common\models\Invoice;
use common\models\Order;
use common\models\OrderAddress;
use common\models\Product;
use common\models\Products;
use common\models\ShoppingAddress;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class OrderController extends Controller
{
    //小程序查看订单订单详细
    function actionOrder($order){
        $or = (new Query()) //查询订单信息
            ->select('order_basic.order_id, from_unixtime(order_basic.create_time) as create_time, from_unixtime(order_basic.payment_time) as payment_time, order_basic.payment_gateway as gateway, order_basic.payment_number as number, description,
            order_basic.order_amount as amount, order_basic.status, order_relationship_address.address, order_relationship_address.name')
            ->from('order_basic')
            ->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->where(['order_basic.order_id' => "$order"])
            ->one();

        $invoice = (new Query()) //查询缴费信息 ->join('inner join', 'order_products', 'order_products.product_id = invoice_id.product_id')
            ->select('user_invoice.year, user_invoice.month, user_invoice.description, user_invoice.invoice_amount as amount, invoice_notes as notes')
            ->from('order_products')
            ->join('inner join', 'user_invoice', 'order_products.product_id = user_invoice.invoice_id')
            ->where(['order_products.order_id' => "$order" ])
            ->orderBy('year DESC, month DESC')
            ->all();

        $detail = ['order' => $or, 'invoice' => $invoice];

        return Json::encode($detail);
    }
    //支付助手确认订单
    function actionCheck($order_id)
    {
        $result = Order::updateAll(['verify' => '1'], 'order_id = :id', [':id' => "$order_id"]);

        $result = Json::encode($result);
        return $result;
    }

    //支付助手作废订单
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

    //支付助手查询当日订单数量
    public function actionCount($fromdate, $todate, $community)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $community = Json::decode($community); //将json数组转换为普通数组

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

    //支付助手查询订单总量
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

    //小程序查询订单总量
    function actionOrders($account_id, $page)
    {
        $order =(new \yii\db\Query())
            ->select('order_basic.order_id, from_unixtime(order_basic.create_time) as create_time, from_unixtime(order_basic.payment_time) as payment_time,
            order_basic.description, order_basic.payment_gateway as way, order_basic.order_amount as amount, order_basic.status, order_relationship_address.address, order_relationship_address.name,
            order_relationship_address.mobile_phone as phone')
            ->from('order_basic')
            ->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->Where(['order_basic.account_id' => "$account_id"])
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

    //生成商户订单
    function actionCreate($account_id, $address_id)
    {
//        $product = $_GET['product']; //接收产品信息
        //暂时赋值
        $product = [['id'=> '8' , 'store' => '37', 'name' => '测试商品', 'price' => '40.8', 'amount' => '81.6', 'count' => '2'],
            ['id'=> '7' , 'store' => '11', 'name' => '睡着饺子', 'price' => '25', 'amount' => '175', 'count' => '7']];

        $amount = array_column($product, 'amount'); //提取金额合计
        $amount = array_sum($amount);//求总金额

        $address = ShoppingAddress::find()
            ->where(['id' => "$address_id"])
            ->asArray()
            ->one();

        $order_id = Order::getOrder02(); //生成订单ID

        $transaction = Yii::$app->db->beginTransaction(); //标记事务
        try{
            $orderBasic = new Order(); //保存订单信息

            $orderBasic->account_id = $account_id;
            $orderBasic->order_id = $order_id;
            $orderBasic->create_time = time();
            $orderBasic->order_type = '2'; //商城订单
            $orderBasic->description = $address['address'];
            $orderBasic->order_amount = $amount;
            $orderBasic->verify = 0;
            $orderBasic->status = 1;

            $orderResult = $orderBasic->save(); //保存订单
            if($orderResult){
                $orderAddress = new OrderAddress(); //添加订单地址簿

                $orderAddress->order_id = $order_id;
                $orderAddress->address = $address['address'];
                $orderAddress->mobile_phone = $address['phone'];
                $orderAddress->name = $address['name'];

                $addressResult = $orderAddress->save(); //保存订单地址

                if($addressResult)
                {
                    foreach ($product as $p){
                        $model = new Products();

                        $model->order_id = $order_id;
                        $model->product_id = $p['id'];
                        $model->product_quantity = $p['count'];
                        $model->store_id = $p['store'];
                        $model->product_name = $p['name'];
                        $model->product_price = $p['price'];

                        $modelResult = $model->save();
                    }
                }
                if($modelResult)
                {
                    $transaction->commit();
                } else{
                    $transaction->rollBack();
                    return false;
                }
            }
        }catch (\Exception $e){
            print_r($e);
            $transaction->rollBack();
        }
        $add = Api::Accumulate($account_id, $amount, $order_id, $income = '1', $type = '2', $status = 2); //积累用户积分

        $order = Order::find()
            ->select('order_id, description, order_amount as amount')
            ->where(['order_id' => "$order_id"])
            ->asArray()
            ->one();

        $order = Json::encode($order);
        return $order;
    }
}
