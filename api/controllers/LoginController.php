<?php
namespace api\controllers;

use common\models\Api;
use common\models\Building;
use common\models\Community;
use common\models\Company;
use common\models\HouseInfo;
use common\models\Login;
use common\models\Realestate;
use common\models\SysCommunity;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * Site controller
 */
class LoginController extends Controller
{
    //裕家人支付助手获取用户openID
    function actionOpenid($appid, $secret, $js_code, $grant_type)
    {
        $output = Login::Wx($appid, $secret, $js_code, $grant_type);

        return $output;
    }
    //裕家人支付助手登录
    function actionPay($unionid)
    {
        $user = (new \yii\db\Query())
            ->select('company.name as company, sys_user.id as id, sys_user.name, sys_user.role, sys_user.phone, sys_user.comment, sys_user_community.community_id as community')
            ->from('sys_user')
            ->join('inner join', 'sys_user_community', 'sys_user_community.sys_user_id = sys_user.id')
            ->join('inner join', 'company', 'company.id = sys_user.company')
            ->where(['sys_user.password' => "$unionid"])
            ->one();
        if(!$user){
            return false;
        }
        $community = $user['community']; //提取小区编码
        $community = explode(',', $community); //分割小区编码
        $comm = Community::find() //查询小区
            ->select('community_name as community')
            ->where(['in', 'community_id', $community])->orderBy('community_id DESC')
            ->asArray()
            ->all();
        $c = array_column($comm, 'community'); //提取小区名称

        $user['community'] = $c; //从新赋值$user中的小区
        $user = ['user' => $user, 'community' => $comm]; //合并数组
        $user = Json::encode($user);

        return $user;
    }
    //小程序登录接口
    public function actionIndex()
    {
        $get = $_GET; //接收数据

        if(isset($get['unionid']))
        {
            $unionid = $get['unionid']; //接收微信账号unionid
            $user = Api::info($unionid); //获取用户信息
            $user=json_decode($user);
            $user = (array)$user; //将数组转换为普通数组
            if(!isset($user['account_id']))
            {
                return false;
            }

            $account_id = $user['account_id']; //提取用户账户ID
            $address = Api::address($account_id);
            $address = Json::decode($address);

            $user = ['user' => $user, 'address' => $address];
            $user = Json::encode($user);

            return $user;
        }else{
            return false;
        }
    }

    //获取公司接口
    public function actionCompany($yuda)
    {
        $company = Company::find()
            ->select('name, id')
            ->where(['parent' => '0'])
            ->orderBy('id ASC')
            ->asArray()
            ->all();

        $name = array_column($company, 'name');
        $company = ['name' => $name, 'company' => $company];
        $company = Json::encode($company);

        return $company;
    }

    //获取公司接口02
    public function actionCompany02($yuda)
    {
        $company = Company::find()
            ->select('name, id')
            ->where(['parent' => '0'])
            ->orderBy('id ASC')
            ->asArray()
            ->one();
        $company = Json::encode($company);

        return $company;
    }

    //获取分公司
    function actionCompanys($company)
    {
        $companys = Company::find()
            ->select('name, id')
            ->where(['parent' => "$company"])
            ->orderBy('id ASC')
            ->all();
        $name = array_column($companys, 'name');
        if(empty($company))
        {
            return false;
        }
        $name = array_column($companys, 'name');
        $companys = ['name' => $name, 'companys' => $companys]; //重新组合返回数组
        $companys = Json::encode($companys);

        return $companys;
    }

    //由公司获取小区
    function actionCommunity($company)
    {
        $community = Community::find()
            ->select('community_name as name, community_id as id')
            ->where(['company' => "$company"])
            ->orderBy('community_name')
            ->asArray()
            ->all();

        if(empty($community))
        {
            return false;
        }

        $name = array_column($community, 'name');
        $community = ['name' => $name, 'communitys' => $community]; //重新组合返回数组
        $community = Json::encode($community);

        return $community;
    }

    //获取楼宇
    function actionBuilding($community)
    {
        $building = Building::find()
            ->select('building_name as name, building_id as id')
            ->where(['community_id' => "$community"])
            ->asArray()
            ->all();
        $name = array_column($building, 'name');
        $building = ['name' => $name, 'buildings' => $building]; //重新组合返回数组
        $building = Json::encode($building);

        return $building;
    }

    //获取单元
    function actionNumber($building)
    {
        $number = Realestate::find()
            ->select('room_number')
            ->where(['building_id' => "$building"])
            ->distinct()
            ->orderBy('room_number')
            ->column();

        $number = Json::encode($number);
        return $number;
    }

    //由单元获取房号
    function actionRoom($building, $number)
    {
        $room = Realestate::find()
            ->select('room_name as name, realestate_id as id')
            ->where(['building_id' => "$building", 'room_number' => "$number"])
            ->orderBy('room_name')
            ->asArray()
            ->all();

        $name = array_column($room, 'name');
        $room = ['name' => $name, 'rooms' => $room]; //重新组合返回数组

        $room = Json::encode($room);
        return $room;
    }

    //验证房号信息
    function actionRealestate($realestate, $name, $phone)
    {
        $info = Realestate::find()
            ->where(['realestate_id' => $realestate, 'owners_name' => $name, 'owners_cellphone' => $phone])
            ->asArray()
            ->one();

        if(!isset($info)){
            $info = HouseInfo::find()
                ->where(['realestate' => "$realestate", 'name' => $name, 'phone' => $phone])
                ->asArray()
                ->one();
        }

        if($info){
            return true;
        }
        return false;
    }

    //裕家人助手获取用户关联的小区
    function actionC($user){
        $community = SysCommunity::find()
            ->select('community_id as community')
            ->where(['sys_user_id' => "$user"])
            ->asArray()
            ->one();

        $community = explode(',',$community['community']); //分割小区编码集合
        $comm = Community::find() //查找小区
            ->select('community_name as community')
            ->where(['in', 'community_id', $community])
            ->orderBy('community_id')
            ->asArray()
            ->all();

        $comm = array_column($comm, 'community');
        $comm = Json::encode($comm);

        return $comm;
    }
}
