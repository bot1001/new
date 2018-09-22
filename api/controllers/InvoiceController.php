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
    //裕家人小程序查询房屋欠费总合
    function actionSum($realestate_id)
    {
        $invoice = Invoice::find()
            ->select('sum(invoice_amount) as amount')
            ->where(['realestate_id' => $realestate_id, 'invoice_status' => '0'])
            ->asArray()
            ->one();

        $invoice = Json::encode($invoice);
        return $invoice;
    }
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

        $p = '10';
        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

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
        $month = 0; //设置默认当月欠费
        foreach($invoice as $i){
            if($i['invoice_status'] == '0')
            {
                $month += $i['invoice_amount']; //遍历求和当月欠费
            }
        }
        $invoice = ['invoice' => $invoice, 'month' => $month]; //重组返回数据

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
        $order =(new \yii\db\Query())
            ->select('order_basic.order_id, from_unixtime(order_basic.create_time) as create_time, from_unixtime(order_basic.payment_time) as payment_time,
            order_basic.description, order_basic.payment_gateway as way, order_basic.order_amount as amount, order_basic.status, order_relationship_address.address, order_relationship_address.name,
            order_relationship_address.mobile_phone as phone')
            ->from('order_basic')
            ->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
            ->Where(['order_basic.order_id' => "$order"])
            ->One();
        $order = Json::encode($order); //转换成json格式数组

        return $order;
    }

    //小程序生成订单
    function actionOrderCreate($realestate, $account)
    {
        $result = Api::Order($realestate, $account);

        return $result;
    }
}
