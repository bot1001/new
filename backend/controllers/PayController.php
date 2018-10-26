<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use app\models\OrderBasic;
use app\models\Pay;

/**
 * TicketController implements the CRUD actions for TicketBasic model.
 require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';
 */
class PayController extends Controller
{	
	//关闭csrf验证
	public $enableCsrfValidation = false;
	
    //调用第三方支付
	public function actionPay()
	{
		$b = $_GET;

		$order_id = $b['order_id'];
	    $order_amount = $b['order_amount'];
	    $description = $b['description'];
	    $community = $b['community'];
	    $gateway = $b['gateway'];
        $paymethod = $b['paymthod']; //支付方式
	    $order_body = '物业缴费';  // 订单描述

        if($order_amount == '0'){ //如果金额为零
            return '2';
        }

		$order = OrderBasic::find()//订单信息
			->select('status,create_time')
			->where(['order_id' => $order_id])
			->one();
		$status = $order['status'];
		$c_time = strtotime($order['create_time']);
		$time = time();
		$t = $time - $c_time;

		$session=Yii::$app->session;

		if($t >= 120 || $status != 1){//判断订单状态和订单有效期，两分钟内有效
		    if($t >= 120){ //如果订单超时则修改订单状态
                OrderBasic::updateAll(['status' => 3], 'order_id = :oid', [':oid' => $order_id]);
            }
			$session->setFlash('cancel', '订单超时或失效，请重新下单！');
			return $this->redirect(Yii::$app->request->referrer);
		}else{
             if($paymethod == 'alipay'){
                 $result = Pay::ali($order_id, $description, $order_amount, $order_body);//生成支付二维码
                 if($result){
                     return true;
                 }
                 return false;

             }elseif($paymethod == 'wx'){
                 $result = Pay::wx($order_id, $description, $order_amount, $type = '1'); //生成支付二维码
                 if($result){
                     return true;
                 }
                 return false;

             }elseif($paymethod == 'jh'){
                 $result = Pay::PayForCode($order_id,$order_amount,$community); //生成支付二维码
                 if($result){
                     return true;
                 }
                 return false;
             }else{
                 return $this->redirect(['ofline', 'order_id' => $order_id, 'order_amount' => $order_amount, 'gateway' => $gateway, 'type' => '1']);
             }
         }
	}

	//建行主动查询
	public function actionJhang($order_id)
	{
		$order = OrderBasic::find()
			->select('payment_number')
			->where(['order_id' => $order_id])
			->asArray()
			->one();
		
		$o = $order['payment_number'];
		
		if($o != ''){ //如果支付编号不为空
			return true;
		}

		return false;
	}

	//支付宝主动查询
    function actionAlipay($order_id, $trade, $or)
    {
        $result = Pay::Alis($order_id, $trade, $or);

        return $result;
    }
	
	public function actionJian() //建行异步回调
	{
		//验签秘钥
		$key = '30819d300d0609';	
		
		if($_GET['REMARK2'] == $key && $_GET['SUCCESS'] == 'Y')
		{
			$post = $_GET;
			$out_trade_no = $post['ORDERID']; //订单编号
			$trade_no = $out_trade_no; //赋值交易流水号
			$total_amount = $post['PAYMENT']; //交易金额 
			$p_time = date(time());
			$gateway = '7';
			
			$pay = Pay::alipay($out_trade_no, $total_amount, $p_time, $trade_no, $gateway); //修改订单相关状态函数
			
			if($pay == '1'){//自动判断并删除过期支付二维码
				Pay::delqr();
			}			
		}
		return 'success';
	}
	
	//线下变更费项状态
	public function actionOfline($order_id, $order_amount, $gateway)
	{
		$change = Pay::change($order_id,$gateway);

		if($change){
            //核实订单类型,1=> 物业缴费；2=>商城订单, 3=>充值服务
            $type = OrderBasic::find()
                ->select(['order_type as type'])
                ->where(['order_id' => "$order_id"])
                ->asArray()
                ->one();
            $type = $type['type'];

            if($type == '1'){
                if($gateway != '4' && $gateway != '5'){
                    return $this->redirect(['/order/print','order_id' => $order_id, 'amount' => $order_amount, 'type' => $type]);
                }else{
                    return $this->redirect(['user-invoice/index','order_id' => $order_id]);
                }
            }else{
                return $this->redirect(['/order/print','order_id' => $order_id, 'amount' => $order_amount, 'type' => $type]);
            }
        }

		return false;
	}
	
	//支付宝异步回调
	public function actionNotify()
	{
		require_once dirname(__FILE__).'../../../vendor/alipay/pagepay/service/AlipayTradeService.php';
		$arr= $_POST;
		
		$config = Yii::$app->params['Alipay'];
		$serviceObj = new \AlipayTradeService($config);
		$serviceObj->writeLog(var_export($arr,true));
		$result = $serviceObj->check($arr);
		
		if($result) {//验证成功
	    
	    //商户订单号
	    $out_trade_no = $_POST['out_trade_no'];
    
	    //支付宝交易号
	    $trade_no = $_POST['trade_no'];
    
	    //交易状态
	    $trade_status = $_POST['trade_status'];
    
		//支付时间
		$time = $_POST['gmt_payment'];
		$p_time = strtotime($time); //转换时间戳
		
		//返回金额
		$total_amount = $_POST['total_amount'];
			    
        if($_POST['trade_status'] == 'TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
		//如果有做过处理，不执行商户的业务程序
		
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
			
         }else if ($_POST['trade_status'] == 'TRADE_SUCCESS'){
			$gateway = '1';
			Pay::alipay($out_trade_no, $total_amount, $p_time, $trade_no, $gateway);
         }
	     //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	     echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";
        	}
	}
	
	//支付宝同步回调
	public function actionReturn()
	{
		require_once dirname(__FILE__).'../../../vendor/alipay/pagepay/service/AlipayTradeService.php';
		$arr= $_GET;
		
		$config = Yii::$app->params['Alipay'];
		$serviceObj = new \AlipayTradeService($config);
		//$serviceObj->writeLog(var_export($arr,true));
		$result = $serviceObj->check($arr);
		
		if($result) {//验证成功
    
    	//商户订单号
    	$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
    
    	//支付宝交易号
    	$trade_no = htmlspecialchars($_GET['trade_no']);

		return $this->redirect(['/order/print', 
                'order_id' => $out_trade_no, 'amount' => $arr['total_amount']
            ]);

        }else {
            //验证失败
            echo "验证失败";
        }
	}

	//微信主动查询
	function actionWei($order_id)
	{
		require_once dirname( __FILE__ ) . '../../../vendor/wx/lib/WxPay.php'; //微信配置文件
		require_once dirname( __FILE__ ) . '../../../vendor/wx/lib/WxPay.Notify.php'; //微信回调文件
		
		$input = new \WxPayOrderQuery();
		$input->SetOut_trade_no($order_id);
		$result = \WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& array_key_exists("trade_state", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["return_code"] == "SUCCESS"
			&& $result["trade_state"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//微信回调地址
	function actionWeixin()
	{
		$post = file_get_contents("php://input"); //接收微信回调信息

		if($post){
            $post = (array) simplexml_load_string($post); //xml数组转换
		    
		    $out_trade_no = $post['out_trade_no'];
            $amount = $post['total_fee'];
		    $total_amount = $amount*0.01;
		    $p_time = time();
		    $trade_no = $post['transaction_id'];
		    $gateway = '2';
				
			//处理订单
		    $result = Pay::alipay($out_trade_no, $total_amount, $p_time, $trade_no, $gateway);
			if($result){
				return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
			}
		}
		return '<xml><return_code><![CDATA[FAIL]]></return_code></xml>';
	}
	
	//通过服务器每隔5五分钟执行的代码
	public function actionUpdate()
    {
        $model = new OrderBasic();
		
		$order = OrderBasic::find()
			->select('order_id, create_time')
			->where(['status' => 1])
			->asArray()
			->all();
		$time = date(time()); //获取当前时间戳
		if(!empty($order)){
			foreach($order as $key => $or )
		    {
		    	$o_time = $or['create_time'];
				$between = $time - $o_time;
				if($between >= 300){
					OrderBasic::updateAll(['status' => 3], 'order_id = :oid', [':oid' => $or['order_id']]);
				}else{
					continue;
				}
		    }
		}
		return false;
    }
}