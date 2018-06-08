<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\OrderBasic;
use app\models\OrderProducts;
use app\models\UserInvoice;
use app\models\Pay;
use dosamigos\qrcode\QrCode;

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

		$order_id = $b['pay']['order_id'];
	    $order_amount = $b['pay']['order_amount'];
	    $description = $b['pay']['description'];
	    $order_body = '物业相关费用';  // 订单描述
		$paymethod = $b['paymethod'];
		
		//创建订单信息
		$order = OrderBasic::find()
			->select('status,create_time')
			->where(['order_id' => $order_id])
			->one();
		$status = $order['status'];
		$c_time = $order['create_time'];
		$time = time();
		$t = $time - $c_time;
		
		$session=Yii::$app->session;
		//判断订单状态
		if($status != 1){
			$session->setFlash('cancel', '订单已失效，请重新下单！');
			return $this->redirect(Yii::$app->request->referrer);
		}else{
		     //判断用户权限
		     if(empty($_SESSION['community'])){
				$session->setFlash('cancel', '权限不足，无法发起支付请求！');
		     	return $this->redirect(Yii::$app->request->referrer);
		     }else{
		     	 //判断订单有效期，两分钟内有效
		         if($t >= 120){
		         	OrderBasic::updateAll(['status' => 3], 'order_id = :oid', [':oid' => $order_id]);
		     		$session->setFlash('can', '1');
		         	return $this->redirect(Yii::$app->request->referrer);
		         }else{
		         	if($status == 1){
		        	if($paymethod == 'alipay'){
		            	    return $this->redirect(['alipay','order_id' => $order_id,
		            	    						   'description' => $description,
		            	    						   'order_amount' => $order_amount,
		            	       						   'order_body' => $order_body]);
		                }elseif($paymethod == 'wx'){
		                	return $this->redirect(['wx','order_id' => $order_id,
		                							   'description' => $description,
		                							   'order_amount' => $order_amount,
		                							   'order_body' => $order_body]);
		                }elseif($paymethod == 'xj'){
		    			    return $this->redirect(['xj', 'order_id' => $order_id]);
		    		    }elseif($paymethod == 'up'){
		    		    	return $this->redirect(['up', 'order_id' => $order_id]);
		    		    }elseif($paymethod == 'yh'){
		    		    	return $this->redirect(['yh', 'order_id' => $order_id]);
		    		    }elseif($paymethod == 'zf'){
		    		    	return $this->redirect(['zf', 'order_id' => $order_id]);
		    		    }elseif($paymethod == 'jh'){
					    	return $this->redirect(['jh','order_id' => $order_id,
		                							   'description' => $description,
		                							   'order_amount' => $order_amount,
		                							   'order_body' => $order_body]);
					    }else{
		    		    	return $this->redirect(['qt', 'order_id' => $order_id]);
		    		    }
		                 }else{
		             	return $this->redirect(Yii::$app->request->referrer);
		             }
		         }
		     }
		}		
	}
	
	//建行接口
	public function actionJh($order_id,$description,$order_amount,$order_body)
	{		
	    $MERCHANTID ="105635000000321";  						//商户号 
	    $POSID="011945623";             						//$_POST["POSID"] ;  
	    $BRANCHID="450000000"; 									//分行号码 
	    $ORDERID=$order_id;                                     //订单号
	    $PAYMENT=$order_amount;									//金额 
	    $CURCODE="01";											//币种 
	    $TXCODE="530550";										//交易类型 
	    $REMARK1="strata fee";									//说明1  千万不能有中文
		
		//备注信息中包含支付公钥前面14位数
	    $REMARK2="30819d300d0609";				                            //说明2  千万不能有中文
	    $RETURNTYPE="2";										//$_POST["RETURNTYPE"] ;  
	    $TIMEOUT="30";											//请求有限时间 
	    $PUB32TR2="42375f6a3517265797d7f877020113";				//$_POST["PUB32TR2"] ;  
	    $bankURL = "https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6" ;	//请求网址
     
	    $f = Pay::PayForCcbQRCode($bankURL,$MERCHANTID,$POSID,$BRANCHID,$ORDERID,$CURCODE,$TXCODE,$PAYMENT,$REMARK1,$REMARK2,$PUB32TR2);
		
		return $this->render('/order/jh',['f' => $f, 'order_id' => $order_id, 'order_amount' => $order_amount]);
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
			return '1'; 
		}else{
			return '0';
		}
	}
	
	public function actionJian()
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
			
			$pay = Pay::alipay($out_trade_no, $total_amount, $p_time, $trade_no); //修改订单相关状态函数
			
			if($pay == '1'){
				//支付完成后自动删除二维码
				$files = glob('images/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
			}			
		}
		return 'success';
	}
	
	//现金支付变更费项状态
	public function actionXj($order_id)
	{
		$change = Pay::change($order_id);
		
		if($change == 1){
			return $this->redirect(['/order/print','order_id' => $order_id]);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	
	//刷卡支付变更费项状态
	public function actionUp($order_id)
	{
		$change = Pay::change($order_id);
		
		if($change == 1){
			return $this->redirect(['/order/print','order_id' => $order_id]);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	
	//银行代缴支付变更费项状态
	public function actionYh($order_id)
	{
		$change = Pay::change($order_id);
		
		if($change == 1){
			return $this->redirect(['user-invoice/index','order_id' => $order_id]);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	
	//政府代缴支付变更费项状态
	public function actionZf($order_id)
	{
		$change = Pay::change($order_id);
		
		if($change == 1){
			return $this->redirect(['user-invoice/index','order_id' => $order_id]);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	
	//其他支付变更费项状态
	public function actionqt($order_id)
	{
		return $this->redirect(Yii::$app->request->referrer); // 费项代码 5
	}
	
	//调用支付宝
	public function actionAlipay()
	{	
		require_once dirname(__FILE__).'/alipay/pagepay/service/AlipayTradeService.php';
        require_once dirname(__FILE__).'/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
		$config = Yii::$app->params['Alipay'];
 
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($_GET['order_id']);

        //订单名称，必填
        $subject = trim($_GET['description']);

        //付款金额，必填
        $total_amount = trim($_GET['order_amount']);

        //商品描述，可空
        $body = trim($_GET['order_body']);

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
	
	//支付宝异步回调
	public function actionNotify()
	{
		require_once dirname(__FILE__).'/alipay/pagepay/service/AlipayTradeService.php';
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
			Pay::alipay($out_trade_no, $total_amount, $p_time, $trade_no);
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
			
		//查询order_id 和金额order_amount
		/*$ord = OrderBasic::find()
				->select('order_id,order_amount')
				->andwhere(['order_id' => $out_trade_no])
				->andwhere(['order_amount' => $arr['total_amount']])
				->asArray()
				->one();
			//print_r($ord);die;
		
		if($ord){
				$transaction = Yii::$app->db->beginTransaction();
				try{
					foreach($ord as $order){
					    //OrderBasic::updateAll(['status' => 3,'payment_number' => 3,'payment_time' => 4,'payment_gateway' => 1]);
					    OrderBasic::updateAll(['status' => 2, //变更状态
					    					   'payment_gateway' => 1, //变更支付方式
					    					   'payment_number' => $trade_no, // 支付编码
					    					   'payment_time' => strtotime($arr['timestamp']) // 支付时间
					    					   ],
					    					   'order_id = :oid', [':oid' => $out_trade_no]
					    				 );
					//exit;
					
					$p_id = OrderProducts::find()
						->select('product_id')
						->where(['order_id' => $out_trade_no])
						->asArray()
						->all();
					
					foreach($p_id as $_id){
						foreach($p_id as $pid){
							//print_r($arr['timestamp']);exit;
							UserInvoice::updateAll(['invoice_status' => 2,
											    'payment_time' => $arr['timestamp'],
											    'update_time' => $arr['timestamp'],
												'order_id' => $out_trade_no],
									            'invoice_id = :oid', [':oid' => $pid['product_id']]
										);
						}
						
					}}
				    
					$transaction->commit();
				}catch(\Exception $e) {
					
                    $transaction->rollback();
                    
                }
			}*/
    		    	
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
	public function actionWx()
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