<?php
namespace api\controllers;

use yii\web\Controller;
use common\models\Order;
use common\models\UserAccount;
use yii\helpers\Json;

/**
 * Site controller
 */
class LoginController extends Controller
{
    //小程序登录接口
    public function actionIndex()
    {
        $get = $_GET; //接收数据
//        $get = Json::decode($get); //解析Json数据

        if(isset($get['openid']))
        {
            $openid = $get['openid']; //接收微信账号openID

            //获取用户信息
            $user = $user = (new \yii\db\Query())
                ->select('user_account.*, user_relationship_realestate.realestate_id, user_data.*')
                ->from('user_account')
                ->join('inner join' , 'user_relationship_realestate', 'user_relationship_realestate.account_id = user_account.account_id')
                ->join('inner join' , 'user_data', 'user_data.account_id = user_account.account_id')
                ->where(['user_account.weixin_openid' => "$openid"])
                ->one();

            $user = Json::encode($user); //普通数组转成Json数组
            return $user;
        }else{
            return false;
        }
    }
}
