<?php
namespace api\controllers;

use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class PayController extends Controller
{
    //裕家人小程序微信支付
    public function actionWx($order_id, $description, $amount, $openid)
    {
        require_once dirname( __FILE__ ) . '/wx/WxPay.Api.php'; //微信配置文件

        $input = new \WxPayUnifiedOrder();//实例化微信支付

        $input->SetBody( $description);//商品标题

        $input->SetOut_trade_no( $order_id ); //订单编号

        $input->SetTotal_fee( $amount*100 ); //订单金额

        $input->SetNotify_url( "http://home.gxydwy.com/pay/weixin" ); //回调地址

        $input->SetTrade_type( "JSAPI" ); //交易类型

        $input->SetOpenid( "$openid" ); // 用户openID

        $result = \WxPayAPI::unifiedOrder($input);
        
        $res = Json::encode($result);

        return $res;
    }
}
