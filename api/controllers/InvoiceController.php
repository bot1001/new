<?php
namespace api\controllers;

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
    //批量获取当月缴费数据
    function actionIndex()
    {
        $get = $_GET;//接收数据

        if(isset($_GET['realestate'])){ //判断是否存在房号ID，如果不存在则返回 false
            $realestate = $get['realestate'];
        }else{
            return false;
        }
        //日期
        $y = date('Y');
        $m = date('m');

        $invoice = Invoice::find()
            ->andFilterWhere(['year' => "$y", 'month' => "$m"])
            ->andFilterWhere(['realestate_id' => "$realestate"]);
        $count = $invoice->count(); //求总数
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '10']); //实例化分页并设置每页显示数量

        $invoice = $invoice->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $invoice = Json::encode($invoice);//转换Json数据

        //判断获取的页数是否为最大
        if(isset($get['page']))
        {
            $page = $get['page'];
            if($get['page'] > $count){
                return false;
            }
        }

        return $invoice;
    }
    //小程序登录接口 单个查询订单
    public function actionOrder()
    {
        $get = $_GET; //接收数据

        if(isset($get['account_id']))//判断是否存在账单ID，不存在则返回false
        {
            $account_id = $_GET['account_id'];
        }else{
            return false;
        }

        $order = Order::find()->andFilterWhere(['account_id' => "$account_id"])->asArray()->One();
        $order = Json::encode($order);

        return $order;
    }

    //批量订单查询接口
    function actionOrders()
    {
        $get = $_GET; // 接收数据

        if(isset($get['account_id']))//判断是否存在账单ID，不存在则返回false
        {
            $account_id = $_GET['account_id'];
        }else{
            return false;
        }

        //创建查询命令
        $orders = Order::find()->andWhere(['account_id' => "$account_id"]);

        $count = $orders->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]); //实例化分页模型并设置每页数量

        $orders = $orders->offset($pagination->offset) //执行查询命令并按照分页数量获取数据
            ->limit($pagination->limit)
            ->all();

        //判断获取的页数是否为最大
        if(isset($get['page']))
        {
            $page = $get['page'];
            if($get['page'] > $count){
                return false;
            }
        }

        $orders = Json::encode($orders);

        return $orders;
    }
}
