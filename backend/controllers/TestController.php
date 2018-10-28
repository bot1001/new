<?php

namespace backend\controllers;

use app\models\CommunityRealestate;
use common\models\Api;
use common\models\Order;
use common\models\UserAccount;
use common\models\UserOpenid;
use Yii;

class TestController extends \yii\web\Controller
{
    //支付宝测试支付
    function actionAlipay($order)
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
            "\"out_trade_no\":\"$order\"," .
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
        $Role = array_column($_SESSION['user'], 'role'); //提取用户角色

        echo '<pre />';
//        print_r($role);
//        print_r($manager);
        print_r($_SESSION);
    }

    function actionTest() //测试
    {
//        require_once(dirname(__FILE__).'/../../vendor/ali-sms/TopSdk.php');
        set_time_limit( 600 );
        ini_set( 'memory_limit', '1024M' ); // 调整PHP由默认占用内存为1024M(1GB)

        //更新用户积分功能
        $order = Order::find()
            ->select('account_id, payment_time as time, order_id, order_amount as amount, order_type as type, status')
            ->where(['and',['>=', 'order_amount', '1'], ['=', 'status' , '2']])
            ->asArray();

        foreach($order ->batch(500) as $d){
            foreach ($d as $or){
                $account_id = $or['account_id'];
                if(strlen($account_id) < 16){ //判断是否是用户下单
                    continue;
                }
                $amount = $or['amount'];
                $amount = number_format($amount, '0');
                $order_id = $or['order_id'];
                $type = $or['type'];
                $income = '1';
                $status = '1';
                $time = time();
                $sql = "insert ignore into store_accumulate(account_id, amount, order_id, income, type, create_time, status, property)
						values('$account_id', '$amount', '$order_id', '$income', '$type', '$time', '$status', '')";
                $sql = Yii::$app->db->createCommand($sql)->execute();
            }
        }

        return true;
    }

    function actionTest01()
    {
        $result = Api::up($order_id = '181028135050');
        if($result){
            return true;
        }

        return false;
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
    }
}
