<?php
namespace api\controllers;

use common\models\Api;
use common\models\Building;
use common\models\Community;
use common\models\Company;
use common\models\HouseInfo;
use common\models\Realestate;
use common\models\SysCommunity;
use yii\web\Controller;
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

        if(isset($get['unionid']))
        {
            $unionid = $get['unionid']; //接收微信账号unionid
            $user = Api::info($get['unionid']);
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
            ->indexBy('id')
            ->column();
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
            ->indexBy('id')
            ->column();
        if(empty($company))
        {
            return false;
        }

        $companys = Json::encode($companys);

        return $companys;
    }

    //由公司获取小区
    function actionCommunity($company)
    {
        $community = Community::find()
            ->select('community_name, community_id')
            ->where(['company' => "$company"])
            ->indexBy('community_id')
            ->orderBy('community_name')
            ->column();
        if(empty($community))
        {
            return false;
        }
        $community = Json::encode($community);

        return $community;
    }

    //获取楼宇
    function actionBuilding($community)
    {
        $building = Building::find()
            ->select('building_name, building_id')
            ->where(['community_id' => $community])
            ->indexBy('building_id')
            ->orderBy('building_name')
            ->column();

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
            ->indexBy('room_number')
            ->orderBy('room_number')
            ->column();

        $number = Json::encode($number);
        return $number;
    }

    //由单元获取房号
    function actionRoom($building, $number)
    {
        $room = Realestate::find()
            ->select('room_name, realestate_id')
            ->where(['building_id' => "$building", 'room_number' => "$number"])
            ->indexBy('realestate_id')
            ->orderBy('room_name')
            ->column();

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

    //获取用户关联的小区
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
