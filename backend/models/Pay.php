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
}
