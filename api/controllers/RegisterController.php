<?php

namespace api\controllers;

use common\models\Area;
use common\models\Community;
use common\models\Realestate;
use common\models\UserOpenid;
use Yii;
use common\models\Api;
use common\models\UserAccount;
use common\models\UserData;
use common\models\UserRealestate;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class RegisterController extends Controller
{
    //小程序手机号码一键注册
    function actionPhone($phone)
    {
        $realestate = (new Query()) //通过手机号码查询房号
            ->select(["community_realestate.realestate_id as id, concat(community_basic.community_name,' ', community_building.building_name, ' ', community_realestate.room_number, '-', community_realestate.room_name) as room"])
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
            ->where(['owners_cellphone' => $phone])
            ->all();

        if($realestate){ //判断查询是否存在
            $realestate = Json::encode($realestate);
            return $realestate;
        }
        return false;
    }
    //支付助手单个小区查询当日注册量
       function actionOne($fromdate, $todate, $community, $page)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $sum = (new \yii\db\Query())
            ->select(["concat( community_basic.community_name,' ', community_building.building_name,' ',community_realestate.room_number, ' ',community_realestate.room_name) as address
            ,user_account.mobile_phone as phone, user_data.real_name as name, from_unixtime(user_data.reg_time) as time"])
            ->from('user_relationship_realestate')
            ->join('inner join', 'community_realestate', 'user_relationship_realestate.realestate_id = community_realestate.realestate_id')
            ->join('inner join', 'community_basic', 'community_basic.community_id= community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id= community_realestate.building_id')
            ->join('inner join', 'user_data', 'user_data.account_id = user_relationship_realestate.account_id')
            ->join('inner join', 'user_account', 'user_account.account_id = user_relationship_realestate.account_id')
            ->andWhere([ 'between','user_data.reg_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['or like', 'community_basic.community_name', $community])
            ->orderBy('user_data.reg_time DESC');

        $count = $sum->count(); //求总数
        $p = '10';

        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => "$p"]); //实例化分页模型并设置每页获取数量

        $count = $sum->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $count = Json::encode($count);
        return $count;
    }

    //按小区分类查询注册量
    function actionDay($fromdate, $todate, $community, $page)
    {
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }
        $community = Json::decode($community);

        $sum = (new \yii\db\Query())
            ->select('community_basic.community_name as community, count(*) as count')
            ->from('user_relationship_realestate')
            ->join('inner join', 'user_account', 'user_account.account_id = user_relationship_realestate.account_id')
            ->join('inner join', 'user_data', 'user_data.account_id = user_account.account_id')
            ->join('inner join', 'community_realestate', 'user_relationship_realestate.realestate_id = community_realestate.realestate_id')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->andWhere([ 'between','user_data.reg_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['or like', 'community_basic.community_name', $community])
            ->groupBy('community_basic.community_name')
            ->orderBy('user_data.reg_time DESC');

        $count = $sum->count(); //求总数
        $p = '10';

        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => "$p"]); //实例化分页模型并设置每页获取数量

        $count = $sum->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $count = Json::encode($count);
        return $count;
    }

    //支付助手查询当日注册总量
    function actionCount($fromdate, $todate, $community)
    {
        $community = Json::decode($community); //数组类型转换
        if($fromdate == $todate)  //如果起始时间和截止时间一样，截止时间自动加一天
        {
            $todate = date('Y-m-d',strtotime("$todate +1 day"));
        }

        $user = (new \yii\db\Query())
            ->select('community_basic.community_name as community, count(*) as count')
            ->from('user_relationship_realestate')
            ->join('inner join', 'user_account', 'user_account.account_id = user_relationship_realestate.account_id')
            ->join('inner join', 'user_data', 'user_data.account_id = user_account.account_id')
            ->join('inner join', 'community_realestate', 'user_relationship_realestate.realestate_id = community_realestate.realestate_id')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->andwhere(['between', 'user_data.reg_time', strtotime($fromdate), strtotime($todate)])
            ->andWhere(['or like', 'community_basic.community_name', $community])
            ->groupBy('community_basic.community_name')
            ->orderBy('user_data.reg_time DESC')
            ->all();

        //重新计算
        $c = array_column($user, 'count'); //提取每个小区数量
        $sum = array_sum($c); //每个小区数量求和

        $comm = count($user); //计算小区总量

        $count = ['community' => $comm, 'count' => $sum];

        $count = Json::encode($count);
        return $count;
    }

    function actionNew($realestate, $phone, $name, $nick, $weixin_openid, $unionid, $face, $gender)
    {
        //查询地区编码起
        $district = (new \yii\db\Query())
            ->select('province_id as province, city_id as city, area_id as area')
            ->from('community_basic')
            ->join('inner join', 'community_realestate', 'community_realestate.community_id = community_basic.community_id')
            ->where(['community_realestate.realestate_id' => $realestate])
            ->one();

        $province = $district['province']; //提取省份
        $city = $district['city']; //提取城市
        $area = $district['area']; //提取地区
        //查询地区编码止

        $account_id = md5($phone); //用户ID
        if($name == ''){ $name = $nick; }//判断姓名是否为空，如果为空自默认和昵称相一致

        $user = UserAccount::find() //验证用户是否存在
            ->where(['mobile_phone' => "$phone"])
            ->asArray()
            ->one();

        if($user){//如果存在则直接更新
            $result = UserAccount::updateAll(['wx_unionid' => $unionid],
                'mobile_phone = :a_id', [':a_id' => $phone]);
            $u_data = UserData::updateAll(['face_path' => $face], 'account_id = :a_id', [':a_id' => $user['account_id']]);

            $new = UserOpenid::find() //判断用户openID是否已存在
                ->where(['open_id' => "$weixin_openid"])
                ->one();

            $r = ''; //设置默认值
            if($new){//如果存在之间更新
                $r = UserOpenid::updateAll(['type' => '2'], 'account_id = :o_id', ['o_id' => "$weixin_openid"]);
            }else{
                $user_openid = new UserOpenid();

                $user_openid->account_id = $user['account_id'];
                $user_openid->open_id = $weixin_openid;
                $user_openid->type = '2';

                $r = $user_openid->save(); //保存用户open id
            }

            if($result || $u_data || $r) //更新完毕后返回用户信息
            {
                $info = Api::info($unionid);//调用函数获取用户信息
                $account_id = $user['account_id']; //提取用户账户ID
                $address = Api::address($account_id); //获取用户地址

                $info = Json::decode($info);
                $address = Json::decode($address);

                $info = ['user' => $info, 'address' => $address];
                $info = Json::encode($info);

                return $info;
            }
        }else{
            $transaction = Yii::$app->db->beginTransaction(); //开启数据库事务
            try {
                $account = new UserAccount(); //实例化模型

                //模型块赋值
                $account->account_id = $account_id;
                $account->user_name = $nick;
                $account->password = 'e10adc3949ba59abbe56e057f20f883e';
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
                    $user_openid->type = '2';

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
                $address = Api::address($account_id); //获取用户地址

                $info = Json::decode($info);
                $address = Json::decode($address);

                $info = ['user' => $info, 'address' => $address];
                $info = Json::encode($info);
                return $info;
            }
        }
        return false;
    }
}