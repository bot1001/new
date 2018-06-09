<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Pay;
use common\models\Order;

class PayController extends Controller
{
	public $enableCsrfValidation = false;//关闭表单验证
	
	//检查用户是否登录
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest){
			$this->redirect(['/login/login']);
			return false;
		}
		return true;
	}
	
	
	//支付接口
	public function actionPay()
	{
		//接收传参
		$get = $_GET;
		$method = $get['method'];
		$pay = $get['pay'];
		
		$order_id = $pay['order_id'];
		$description = $pay['description'];
		$amount = $pay['order_amount'];
		$body = '物业缴费';  // 订单描述
		
		if($method == 'alipay')
		{
		    return $this->redirect(['alipay','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}elseif($method == 'wx'){
		    return $this->redirect(['wx','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}else{
			return $this->redirect(['jh','order_id' => $order_id,
		    						 'description' => $description,
		    						 'amount' => $amount,
		       					     'body' => $body]);
		}
	}
	
	//调用支付宝接口
	public function actionAlipay($order_id,$description,$amount,$body)
	{
		require_once dirname(__FILE__).'/alipay/pagepay/service/AlipayTradeService.php';
        require_once dirname(__FILE__).'/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
		$config = Yii::$app->params['Alipay'];
 
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($order_id);

        //订单名称，必填
        $subject = trim($description);

        //付款金额，必填
        $total_amount = trim($amount);

        //商品描述，可空
        $body = trim($body);

	    //构造参数
	    $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
	    $payRequestBuilder->setBody($body);
	    $payRequestBuilder->setSubject($subject);
	    $payRequestBuilder->setTotalAmount($total_amount);
	    $payRequestBuilder->setOutTradeNo($out_trade_no);
 
		$aop = new \AlipayTradeService($config);
    
	    /**
	     * pagePay 电脑网站支付请求
	     * @param $builder 业务参数，使用buildmodel中的对象生成。
	     * @param $return_url 同步跳转地址，公网可以访问
	     * @param $notify_url 异步通知地址，公网可以访问
	     * @return $response 支付宝返回的信息
 	    */
	    $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    
	    //输出表单
	    var_dump($response);
	}
	
	//调用微信支付接口
	public function actionWx($order_id,$description,$amount,$body)
	{
		echo 'text';
	}
	
	//调用建行支付接口
	public function actionJh($order_id,$description,$amount,$body)
	{		
	    $MERCHANTID ="105635000000321";  						//商户号 
	    $POSID="011945623";             						//$_POST["POSID"] ;  
	    $BRANCHID="450000000"; 									//分行号码 
	    $ORDERID=$order_id;                                     //订单号
	    $PAYMENT=$amount;									//金额 
	    $CURCODE="01";											//币种 
	    $TXCODE="530550";										//交易类型 
	    $REMARK1="strata fee";									//说明1  非中文
		
		//备注信息中包含支付公钥前面14位数
	    $REMARK2="30819d300d0609";				                //说明2  非中文
	    $RETURNTYPE="2";										//$_POST["RETURNTYPE"] ;  
	    $TIMEOUT="30";											//请求有限时间 
	    $PUB32TR2="42375f6a3517265797d7f877020113";				//$_POST["PUB32TR2"] ;  
	    $bankURL = "https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6" ;	//请求网址
     
	    $f = Pay::PayForCcbQRCode($bankURL,$MERCHANTID,$POSID,$BRANCHID,$ORDERID,$CURCODE,$TXCODE,$PAYMENT,$REMARK1,$REMARK2,$PUB32TR2);
		
		return $this->render('/order/jh',['f' => $f, 'order_id' => $order_id, 'amount' => $amount]);
	}
	
	//建行主动查询
	public function actionJhang($order_id)
	{
		$order = Order::find()
			->select('payment_number')
			->where(['order_id' => $order_id])
			->asArray()
			->one();
		
		$o = $order['payment_number'];
		
		if($o != ''){ //如果支付编号不为空
			return '1'; 
		}else{
			return '0';
		}
	}
	
	//支付宝同步回调
	public function actionReturn()
	{
		require_once dirname(__FILE__).'/alipay/pagepay/service/AlipayTradeService.php';
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
		    		    	
		//echo "验证成功<br />支付宝交易号：".$trade_no;
		return $this->redirect(['/order/print', 
                'order_id' => $out_trade_no,
            ]);

        }else {
            //验证失败
            echo "验证失败";
        }
	}
	
	//微信支付
	public function actionW()
	{
		require_once dirname( __FILE__ ) . '/wx/lib/WxPay.Api.php'; //微信配置文件
		
		$input = new \WxPayUnifiedOrder();//实例化微信支付
		
		$input->SetBody( "test" );//商品标题
		
		$input->SetOut_trade_no( date( "YmdHis" ) ); //订单编号
		
		$input->SetTotal_fee( "1" ); //订单金额
				
		$input->SetNotify_url( "http://paysdk.weixin.qq.com/example/notify.php" ); //回调地址
		
		$input->SetTrade_type( "NATIVE" ); //交易类型
		
		$input->SetProduct_id( "123456789" ); // 商品编码
		
		$result = \WxPayAPI::unifiedOrder($input);
		
		print_r($result);exit;
		
		//获取支付链接
		$url = $result['code_url'];
				
		return $this->redirect(['/order/wx', 
                'url' => $url,
            ]);		
	}
	
	//用户退出
	public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}