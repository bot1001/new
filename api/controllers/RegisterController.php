<?php

namespace api\controllers;

use common\models\UserOpenid;
use Yii;
use common\models\Api;
use common\models\UserAccount;
use common\models\UserData;
use common\models\UserRealestate;
use yii\helpers\Json;
use yii\web\Controller;

class RegisterController extends Controller
{
    //查询当日注册总量
    function actionCount()
    {
        $time = strtotime(date('Y-m-d'));
        $count = UserData::find()
            ->select('count(account_id) as count')
            ->where([ '>=','reg_time', $time])
            ->orderBy('reg_time DESC')
            ->asArray()
            ->one();
        $count = Json::encode($count);
        return $count;
    }

    function actionNew($realestate, $phone, $name, $nick, $password, $weixin_openid, $unionid, $face, $gender, $province, $city, $area)
    {
        //判断地区编码长度
        if(strlen($province) != '6' || strlen($city) != '6' || strlen($area) != '6')
        {
            return false;
        }

        $account_id = md5($phone); //用户ID
        if($name == ''){ $name = $nick; }//判断姓名是否为空，如果为空自默认和昵称相一致

        $user = UserAccount::find() //验证用户是否存在
            ->where(['wx_unionid' => "$unionid"])
            ->asArray()
            ->one();

        if($user){//如果存在则直接更新
            $result = UserAccount::updateAll(['wx_unionid' => $unionid],
                'mobile_phone = :a_id', [':a_id' => $phone]);
            $u_data = UserData::updateAll(['face_path' => $face], 'account_id = :a_id', [':a_id' => $user['account_id']]);

            $user_openid = new UserOpenid();

            $user_openid->account_id = $account_id;
            $user_openid->open_id = $weixin_openid;
            $user_openid->type = '1';

            $user_openid->save(); //保存用户open id

            if($result || $u_data || $user_openid) //更新完毕后返回用户信息
            {
                $info = Api::info($unionid);//调用函数获取用户信息
                return $info;
            }
        }else{
            $transaction = Yii::$app->db->beginTransaction(); //开启数据库事务
            try {
                $account = new UserAccount(); //实例化模型

                //模型块赋值
                $account->account_id = $account_id;
                $account->user_name = $nick;
                $account->password = md5($password);
                $account->mobile_phone = $phone;
                $account->wx_unionid = $unionid;
                $account->new_message = '0';
                $account->status = '1';

                $result = $account->save(); // 保存
                if($result){
                    $id = Yii::$app->db->getLastInsertID(); //最新插入的数据ID

                    $ship = new UserRealestate();
                    $ship->account_id = $account_id;
                    $ship->realestate_id = $realestate;

                    $r = $ship->save(); //保存用户关联信息

                    $userdata = new UserData(); //实例化用户信息模型

                    $userdata->account_id = $account_id;
                    $userdata->real_name = $nick;
                    $userdata->gender = $gender;
                    $userdata->face_path = $face;
                    $userdata->province_id = $province;
                    $userdata->city_id = $city;
                    $userdata->area_id = $area;
                    $userdata->nickname = $nick;

                    $u = $userdata->save(); //保存用户资料

                    $user_openid = new UserOpenid();

                    $user_openid-> account_id = $account_id;
                    $user_openid-> open_id = $weixin_openid;
                    $user_openid->type = '1';

                    $user_openid->save();
                }
                if($result && $r && $u){
                    $transaction->commit(); //结束事务管理
                }else{
                    $transaction->rollback();
                    return false;
                }
            }catch(\Exception $e){
                $transaction->rollback();
                return false;
            }
            if($result && $r && $u){
                $info = Api::info($unionid);//调用函数获取用户信息
                return $info;
            }
        }
        return false;
    }
}