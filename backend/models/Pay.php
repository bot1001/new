<?php

namespace app\models;

use Yii;
use dosamigos\qrcode\QrCode;

class Pay extends \yii\db\ActiveRecord
{
    public static function HttpReq_GET($URL) {
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
}