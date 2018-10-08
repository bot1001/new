<?php

namespace app\models;

use Yii;
use dosamigos\qrcode\QrCode;

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
	public static function PayForCode($order_id,$order_amount,$community, $type)
    {
        $MERCHANTID ="105635000000321";  						//商户号
        $POSID="011945623";             						//$_POST["POSID"] ;
        $BRANCHID="450000000"; 								//分行号码
        $ORDERID=$order_id;                                     //订单号
        $PAYMENT=$order_amount;									//金额
        $CURCODE="01";											//币种
        $TXCODE="530550";										//交易类型
        $REMARK1= $community;									//说明1  千万不能有中文

        //备注信息中包含支付公钥前面14位数
        $REMARK2="30819d300d0609";				                            //说明2  千万不能有中文
        $RETURNTYPE="2";										//$_POST["RETURNTYPE"] ;
        $TIMEOUT="30";											//请求有限时间
        $PUB32TR2="42375f6a3517265797d7f877020113";				//$_POST["PUB32TR2"] ;
        $bankURL = "https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6" ;	//请求网址

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

        $tmpStr = Pay::qr($ORDERID, $URL); //生成二维码

		if ( $tmpStr && $type == '3' ) { //判断是否为充值订单
		    return true;
		}
		return $tmpStr;
	}
	//生成二维码
	static function qr($order_id, $url)
	{
		$dir = "images"; //二维码保存路径
		if ( !is_dir($dir) ) { //如果文件夹不存在，则创建此文件夹
			mkdir($dir);
		}
		$qc = $dir."/".$order_id.'.png'; //重命名二维码
        Pay::del($order_id);//判断并删除同名二维码
		$errorCorrectionLevel = 'H';//容错级别   
		
		QRcode::png($url,$qc,$errorCorrectionLevel, 10, 2); //创建二维码
		if ( !file_exists($qc) ) {
			return false ;  //如果二维码生成失败返回false
		}
		return $qc;
	}

	//判断并删除同名二维码
    static function del($order){
        $dir = "images"; //二维码保存路径
        if ( !is_dir($dir) ) {
            mkdir($dir);
        }
        $tmpStr = $dir."/".$order.'.png'; //重命名二维码
        if(is_file($tmpStr)){ //判断如果存在同名二维码
            unlink($tmpStr);
        }
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
                        if($invoice){ //如果修改成功
                            $transaction->commit();
                            return true;
                        }
                    }
                    $transaction->rollBack();
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
                        if($invoice){
                            $transaction->commit();
                            Pay::delqr(); //自动判断并删除过期支付二维码
                            return true;
                        }
                    }
                    $transaction->rollback();
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
                    if($order){
                        $transaction->commit();
                        Pay::delqr();
                        return true;
                    }
                    $transaction->rollBack();
                }catch(\Exception $e) {
                    $transaction->rollback();
                }
            }
		}

		return false;
	}

	//自动判断并删除过期支付二维码
    static function delqr(){
        //支付完成后自动删除二维码
        $files = glob('images/*');
        $time = time();

        foreach ($files as $file) {
            $filetime = filemtime($file);//二维码创建时间
            if ($time - $filetime >= 600) { //判断并删除一个小时内创建的二维码
                unlink($file);
            }
        }
    }

	//创建微信支付链接
    static function wx($order_id, $description, $order_amount, $type)
    {
        require_once dirname( __FILE__ ) . '../../../vendor/wx/lib/WxPay.php'; //微信配置文件

        $input = new \WxPayUnifiedOrder();//实例化微信支付

        $input->SetBody( $description);//商品标题

        $input->SetOut_trade_no( $order_id ); //订单编号

        $input->SetTotal_fee( $order_amount*100 ); //订单金额

        $input->SetNotify_url( "https://home.gxydwy.com/pay/weixin" ); //回调地址

        $input->SetTrade_type( "NATIVE" ); //交易类型

        $input->SetProduct_id( "123456789" ); // 商品编码

        $result = \WxPayAPI::unifiedOrder($input);

        if($result['code_url']){//获取支付链接
            $url = $result['code_url'];
        }else{
            return false;
        }
        Pay::del($order_id); //判断并删除同名二维码
        $img = Pay::qr($order_id, $url);//生成支付二维码

        if($type == 3){ //判断是否是充值订单
            if (is_file($img)) { //判断创建时间是否为一个小时前
                return true;
            }
            return false;
        }else {
            return $img;
        }

        return false;
    }
}