<?php

namespace backend\controllers;

use app\models\CommunityRealestate;
use common\models\Order;
use common\models\UserAccount;
use common\models\UserOpenid;
use Yii;

class TestController extends \yii\web\Controller
{
    //支付宝测试支付
    function actionAlipay()
    {
        require_once dirname(__FILE__).'../../../vendor/alipay/AopSdk.php';
        $config = Yii::$app->params['Alipay'];

        $aop = new \AopClient ();
        $aop->gatewayUrl = $config['gatewayUrl'];
        $aop->appId = $config['app_id'];
        $aop->rsaPrivateKey = $config['merchant_private_key'];
        $aop->alipayrsaPublicKey= $config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $aop->signType = 'RSA2';

        $request = new \AlipayTradeQueryRequest ();
        $request->setBizContent("{" .
            "\"out_trade_no\":\"180409445612\"," .
            "\"trade_no\":\"\"," .
            "\"org_pid\":\"\"" .
            "  }");
        $result = $aop->execute ( $request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode;
        $response = (array)$resultCode;//强制转换数据

        echo '<pre />';
        print_r($response);
    }

	public function actionIndex()
	{
	    return false;
        ini_set( 'memory_limit', '2048M' );// 调整PHP由默认占用内存为2048M(2GB)
        set_time_limit(0);
		$community=$_SESSION['community'];
		$i = 0;
		$f = 0;
		foreach ($community as $c){
		    $realestate=CommunityRealestate::find()
                ->select('realestate_id as id, community_id as community, building_id as building ,room_number as number, room_name as name')
                ->where(['in', 'community_id', $c])
                ->asArray()
                ->all();
		    foreach ($realestate as $r){
		        $id=$r['id'];
		        $number=$r['number'];
		        $name=$r['name'];

		        if(strlen($number) < '2'){
		            $number = str_pad($number, 2, '0', STR_PAD_LEFT);
                }

		        $room=explode('-', $name);
		        $room01 = reset($room); //拆分后的单元 UPDATE `yuhomepeople`.`community_realestate` SET `room_name`='0702' WHERE  `realestate_id`=32784;
                $room01=str_pad($room01,2,"0",STR_PAD_LEFT); //不满2位数的自动补0

                $room02 = end($room); //拆分后的房号

                if($number === $room01 || strlen($room02) == '3')
                {
                    $room02=str_pad($room02,4,"0",STR_PAD_LEFT); //不满4位数的自动补0
                    $sql = "update ignore community_realestate set room_name= '$room02' , room_number = '$number' where  realestate_id=$id";
                    $result = Yii::$app->db->createCommand( $sql )->execute();
                }

                if(isset($result))
                {
                    if($result == '1'){
                        $i++; //计算成功修改个数
                    }else{
                        $f++;
                    }
                }else{
                    $f++; //计算失败个数
                }
            }
        }

        echo '成功修改: '.$i.' 个'.'失败：'.$f.' 个';
	}

	//message
    function actionManager()
    {
        $manager = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id); //获取当前用户角色信息
        $role = Yii::$app->authManager->getRole( 'admin' ); //获取指定角色信息
        $childAll = Yii::$app->authManager->getChildren( $role->name );

        echo '<pre />';
//        foreach($manager as $key => $m){
            print_r($role);
//        }
    }

    function actionTest()
    {
        $invoice = (new \yii\db\Query())
            ->select('count(*)')
            ->from('user_invoice')
            ->groupBy('realestate_id')
            ->all();
        print_r(count($invoice));
    }

    //处理用户openID表
    function actionOpen()
    {
        $user_accout = UserAccount::find()
            ->select('account_id, weixin_openid')
            ->where(['!=', 'wx_unionid', ''])
            ->asArray()
            ->all();

        foreach ( $user_accout as $account){
            $user_open = new UserOpenid();

            $user_open->account_id = $account['account_id'];
            $user_open->open_id = $account['weixin_openid'];
            $user_open->type = '1';

            $user_open->save();
        }

//        echo '<pre />';
//        print_r($user_accout);
    }
}
