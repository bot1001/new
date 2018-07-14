<?php
namespace api\controllers;

use common\models\Api;
use common\models\Invoice;
use common\models\Order;
use yii\data\Pagination;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * Site controller
 */
class InvoiceController extends Controller
{
    //批量获取缴费数据
    function actionIndex($realestate, $page)
    {
        //日期
        $y = date('Y');
        $m = date('m');

        $invoice = Invoice::find()
            ->andFilterWhere(['realestate_id' => "$realestate"])
            ->orderBy('invoice_status ASC, year DESC, month DESC');

        $count = $invoice->count(); //求总数
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '10']); //实例化分页并设置每页显示数量

        $invoice = $invoice->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $invoice = Json::encode($invoice);//转换Json数据
        if(empty($invoice)){
            return false;
        }else{
            return $invoice;
        }
    }

    //当月缴费数据
    function actionInvoice($realestate)
    {
        $invoice = Invoice::find()
            ->andWhere(['realestate_id' => "$realestate"])
            ->andWhere(['year' => date('Y'), 'month' => date('m')])
            ->asArray()
            ->all();
        $invoice = Json::encode($invoice);
        if(empty($invoice))
        {
            return false;
        }
        return $invoice;
    }
    //小程序单个查询订单
    public function actionOrder($order)
    {
        $order = Order::find()
            ->andFilterWhere(['order_id' => "$order"])
            ->asArray()
            ->One();
        $order = Json::encode($order);

        return $order;
    }

    //批量订单查询接口
    function actionOrders($account_id, $page)
    {
        //创建查询命令
        $orders = Order::find()->andWhere(['account_id' => "$account_id"])->orderBy('status ASC, order_id DESC');

        $count = $orders->count();  //求总数
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]); //实例化分页模型并设置每页数量

        $orders = $orders->offset($pagination->offset) //执行查询命令并按照分页数量获取数据
            ->limit($pagination->limit)
            ->all();

        if(empty($orders)){   return false; } //判断获取的页数是否为最大
        $orders = Json::encode($orders);

        return $orders;
    }

    //生成订单
    function actionOrderCreate($realestate, $account)
    {
        $result = Api::Order($realestate, $account);
        $result = Json::encode($result);

        return $result;
    }
}
