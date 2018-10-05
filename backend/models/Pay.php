<?php

namespace app\models;

use Yii;
use dosamigos\qrcode\QrCode;
use app\models\OrderProducts;
use app\models\UserInvoice;
use app\models\OrderBasic;

class Pay extends \yii\db\ActiveRecord
{
    public static function HttpReq_GET($URL) 
	{
		    $ch=curl_init(); //设置选项，包括URL
		    curl_setopt($ch,CURLOPT_URL,$URL);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		    curl_setopt($ch,CURLOPT_HEADER,0);
		    $output=curl_exec($ch); 
		    curl_close($ch);
		    return $output ;
	    }
	
	   //增加交易码和币种的传入处理 20180317
	public static function PayForCcbQRCode($bankURL,$MERCHANTID,$POSID,$BRANCHID,$ORDERID,$CURCODE,$TXCODE,$PAYMENT,$REMARK1,$REMARK2,$PUB32TR2) 
    {
 
		$tmpStr = "MERCHANTID=".$MERCHANTID."&POSID=".$POSID."&BRANCHID=".$BRANCHID."&ORDERID=".$ORDERID.
 		 	 "&PAYMENT=".$PAYMENT."&CURCODE=".$CURCODE."&TXCODE=".$TXCODE."&REMARK1=".$REMARK1.
		 	 "&REMARK2=".$REMARK2."&RETURNTYPE=3&TIMEOUT=";
		$URL0 = $tmpStr."&PUB=".$PUB32TR2;
		$URL = $bankURL."&".$tmpStr."&MAC=".md5($URL0);
		$tmpStr = Pay::HttpReq_GET($URL); //第一次发起支付请求
		
		$rets = json_decode($tmpStr,true) ;
		if ( strtoupper($rets["SUCCESS"]) != "TRUE" ) {
			echo "第一次请求支付";
			return null ;
		}		
		$URL = $rets["PAYURL"] ;
		$tmpStr = Pay::HttpReq_GET($URL); //第二次发起支付请求
		
		$rets = json_decode($tmpStr,true);
		if ( strtoupper($rets["SUCCESS"]) != "TRUE" ) {
			echo "第二次请求支付";
			return null ;
		}		
		$URL = $rets["QRURL"] ; //二维码初始数据
		$URL = urldecode($URL); //解码二维码
		
		$URL = iconv("gb2312","utf-8//IGNORE",$URL);
		
		$dir = "images"; //二维码保存路径
		if ( !is_dir($dir) ) {
			mkdir($dir);
		}
		$tmpStr = $dir."/QR_0_".$ORDERID.'.png'; //重命名二维码
		//$logo = "./image/logo01.png" ;
		
		QRcode::png($URL,$tmpStr); //创建二维码
		if ( !file_exists($tmpStr) ) {
			echo "生成二维码图片处理失败";
			return unll ;
		}
		return $tmpStr;
	}
	
	static function Wx($order_id, $url)
	{
		$dir = "images"; //二维码保存路径
		if ( !is_dir($dir) ) { //如果文件夹不存在，则创建此文件夹
			mkdir($dir);
		}
		$qc = $dir."/".$order_id.'.png'; //重命名二维码
		$errorCorrectionLevel = 'H';//容错级别   
		
		QRcode::png($url,$qc,$errorCorrectionLevel, 10, 2); //创建二维码
		if ( !file_exists($qc) ) {
			echo "生成二维码图片处理失败";
			return unll ;
		}
		return $qc;
	}
	
	//微信支付打印日志
	static function Log($post)
	{
		$dir = "log"; //日志保存路径
		if( !is_dir($dir)) //判断路径是否存在
		{
			mkdir($dir); // 如存在则创建
		}
		
		$filename = "./log/".date("Y-m-d").".log";
		$date = date("Y-m-d H:i:s");
		foreach($post as $key => $p){
			$str = $date."\n"."$key"."=>"."$p"."\n";
            file_put_contents($filename, $str, FILE_APPEND|LOCK_EX);
		}
        
        return null;		
	}
	
	public static function Jh($order_id)
	{
		//变量赋值
	    $MERCHANTID ="105635000000321";  						//$_GET["MERCHANTID"] ;  
	    $POSID="011945623";             						//$_GET["POSID"] ;  
	    $BRANCHID="450000000"; 									//$_GET["BRANCHID"] ;  
	    $ORDERID= $order_id;             			            //查询订单号 
	    $PASSWORD="Yudawuye";							        //商户对应的管理员密码 从银行处获取
	    $TXCODE="410408";										//$_GET["TXCODE"] ;  
	    $TYPE="0";												//0 支付流水 1 退款流水
	    $KIND="0";												//0 未结算流水 1 已结算流水
	    $STATUS="3";											//0失败 1成功 2不确定 3全部（已结算流水查询不支持全部）
	    $PAGE="1";												//想要取第几页流水 总共多少页必须从第一页的响应包中获取
	    $bankURL = "https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain";				//$_GET["bankURL"] ; 
    
	    $param0 = "MERCHANTID=".$MERCHANTID."&BRANCHID=".$BRANCHID."&POSID=".$POSID."&ORDERDATE=".date("Ymd").
	          "&BEGORDERTIME=00:00:00&ENDORDERTIME=23:59:59&ORDERID=".$ORDERID."&QUPWD=&TXCODE=".$TXCODE."&TYPE=".$TYPE."&KIND=".$KIND."&STATUS=".$STATUS.
	          "&SEL_TYPE=3&PAGE=".$PAGE."&OPERATOR=&CHANNEL=";
	    $param1 = "MERCHANTID=".$MERCHANTID."&BRANCHID=".$BRANCHID."&POSID=".$POSID."&ORDERDATE=".date("Ymd").
        	   "&BEGORDERTIME=00:00:00&ENDORDERTIME=23:59:59&BEGORDERID=&ENDORDERID=&QUPWD=".$PASSWORD.
	    	   "&TXCODE=".$TXCODE."&TYPE=".$TYPE."&KIND=".$KIND."&STATUS=".$STATUS."&ORDERID=".$ORDERID."&PAGE=".$PAGE."&CHANNEL=&SEL_TYPE=3&OPERATOR=&MAC=".md5($param0);        
	    $URL = $bankURL."?".$param1;
				
		$u = curl_init(); //开启会话
        // 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL, $URL);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($u,CURLOPT_HEADER,0);
		
		curl_setopt($u, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($u, CURLOPT_SSL_VERIFYHOST, false); //
		
		$error = curl_error($u); //获取返回的数据包
        // 执行并获取HTML文档内容
        $date = curl_exec($u);
        curl_close($u);
		
		$d = explode(' ', $date);
		$end = end($d);
		
		return $end;
	}
	
	public static function Xml($arr)
	{    	
		$xml= "<xml>print_r($arr)</appid></xml>";//XML文件
		
		$objectxml = (array) simplexml_load_string($xml);//将文件转换成 对象  
        $xmljson= json_encode($objectxml );//将对象转换个JSON  
        $xmlarray=json_decode($xmljson,true);//将json转换成数组
		
        return $xmlarray; 
    }
	
	//物业缴费后台线下缴费状态变更
	public static function change($order_id, $gateway)
	{
	    //核实订单类型,1=> 物业缴费；2=>商城订单, 3=>充值服务
        $type = OrderBasic::find()
            ->select(['order_type as type'])
            ->where(['order_id' => "$order_id"])
            ->asArray()
            ->one();

        if($type['type'] == '1'){
            //获取订单费项ID
            $i_id = OrderProducts::find()
                ->select('product_id')
                ->where(['order_id' => $order_id])
                ->asArray()
                ->all();

            if($i_id){
                $transaction = Yii::$app->db->beginTransaction(); //$transaction = Yii::$app->db->beginTransaction();
                try{
                    //变更订单状态
                    $order =  OrderBasic::updateAll(['payment_time' => time(),
                        'payment_gateway' => $gateway,
                        'payment_number' => $order_id,
                        'status' => 2],
                        'order_id = :o_id', [':o_id' => $order_id]
                    );
                    if($order){
                        foreach($i_id as $i){//变更费项状态
                            $invoice = UserInvoice::updateAll(['payment_time' => time(),
                                'update_time' => time(),
                                'invoice_status' => $gateway,
                                'order_id' => $order_id],
                                'invoice_id = :product_id', [':product_id' => $i['product_id']]
                            );
                        }

                        $transaction->commit();
                        return true;
                    }
                }catch(\exception $e){
                    $transaction->rollback();
                }
            }
        }else{
            $transaction = Yii::$app->db->beginTransaction();
            try{
                //变更订单状态
                $order = OrderBasic::updateAll(['payment_time' => time(),
                    'payment_gateway' => $gateway,
                    'payment_number' => $order_id,
                    'status' => '2'],
                    'order_id = :o_id', [':o_id' => $order_id]);
                $transaction->commit();

                if ($order){ //如果修改成功
                    return true;
                }
            }catch(\Exception $e){
                $transaction->rollBack();
            }
        }
		return false;
	}
	
	//支付宝异步回调处理订单
	public static function alipay($out_trade_no, $total_amount, $p_time, $trade_no, $gateway)
	{
		//查询order_id 和金额order_amount
		$ord = OrderBasic::find()
				->select('order_id, order_amount, order_type as type')
				->andwhere(['order_id' => "$out_trade_no"])
				->andwhere(['order_amount' => "$total_amount"])
				->asArray()
				->one();
		
		if($ord){
		    if($ord['type'] == '1') //如果订单为物业缴费
            {
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $order = OrderBasic::updateAll(['status' => 2, //变更订单状态
                        'payment_gateway' => $gateway, //变更支付方式
                        'payment_number' => $trade_no, // 支付流水号
                        'payment_time' => $p_time // 支付时间
                    ],
                        'order_id = :oid', [':oid' => $out_trade_no]
                    );

                    if($order){
                        $p_id = OrderProducts::find()
                            ->select('product_id, sale')
                            ->where(['order_id' => "$out_trade_no"])
                            ->asArray()
                            ->all();

                        foreach($p_id as $pid)
                        {
                            if($pid['sale'] == 1){ //判断费项是否为优惠
                                $status = '4';
                            }else{
                                $status = $gateway;
                            }

                            $invoice = UserInvoice::updateAll(['invoice_status' => $status,
                                'payment_time' => $p_time,
                                'update_time' => $p_time,
                                'order_id' => $out_trade_no],
                                'invoice_id = :oid', [':oid' => $pid['product_id']]
                            );
                        }
                    }
                    if($invoice){
                        $transaction->commit();
                        return true;
                    }else{
                        $transaction->rollback();
                    }
                }catch(\Exception $e) {
                    $transaction->rollback();
                }
            }else{
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $order = OrderBasic::updateAll(['status' => 2, //变更订单状态
                        'payment_gateway' => $gateway, //变更支付方式
                        'payment_number' => $trade_no, // 支付流水号
                        'payment_time' => $p_time // 支付时间
                    ],
                        'order_id = :oid', [':oid' => $out_trade_no]
                    );
                    $transaction->commit();
                }catch(\Exception $e) {
                    $transaction->rollback();
                }
            }
		}

		return false;
	}
}