<?php

namespace backend\models;
use app\models\SysCommunity;
use app\models\CommunityBasic;
use common\models\Store;

class Login extends \yii\db\ActiveRecord
{
    //物业收银账户登陆
    static function wuye($id, $session)
    {
        $user = (new \yii\db\Query())->select('
			         sys_user.id as id, 
			         company.name as company,
			         sys_user.name, 
			         sys_user.role, 
			         sys_user.salt as market, 
			         sys_user.phone, 
			         sys_user.salt, 
			         sys_user.comment, 
			         sys_user.create_id as create, 
			         sys_user.create_time,
			         auth_assignment.item_name as Role')
            ->from('sys_user')
            ->join('inner join', 'company', 'company.id = sys_user.company')
            ->join('inner join', 'auth_assignment', 'auth_assignment.user_id = sys_user.id')
            ->where(['sys_user.id' => $id])
            ->all();

        if(empty($user)){
            return false;
        }
        $session['user'] = $user; //将用户信息添加到session
        //获取用户绑定小区
        $syscommuntiy = SysCommunity::find()
            ->select('community_id')
            ->where(['sys_user_id' => $session['user']['0']['id']])
            ->asArray()
            ->one();
        if(!$syscommuntiy){
            return false;
        }
        //拆分用户关联小区
        $s = explode(',',$syscommuntiy['community_id']);
        $s = array_unique($s);

        //获取关联小区名称
        $community = CommunityBasic::find()
            ->select('community_name, community_id')
            ->where(['in', 'community_id', $s]);
        $community_name = $community->asArray()->all();
        $community_id = $community->orderBy('community_id')->indexBy('community_id')->column();

        $session['community_id'] = $community_id; //用户关联小区，如 [55] => 明月园
        $session['community_name'] = $community_name; //用户关联小区数组，如[community_name] => 明月园
        $session['community'] = $s; //将用户关联的小区编码添加到session， 如[0] => 55

        return true;
    }

    //管理员账户登陆
    static function manager($id, $session)
    {
        $login = self::wuye($id, $session); //暂时设定物业账户登陆
        if ($login){
            return true;
        }
        return false;
    }

    //商户商户登陆
    static function market($id, $session)
    {
        $user = (new \yii\db\Query())->select('
			         sys_user.id as id, 
			         store_account.store_id as store,
			         sys_user.name, 
			         store_account.role, 
			         sys_user.phone, 
			         sys_user.salt, 
			         sys_user.salt as market, 
			         sys_user.comment, 
			         sys_user.create_id as create, 
			         sys_user.create_time,
			         auth_assignment.item_name as Role')
            ->from('sys_user')
            ->join('inner join', 'store_account', 'store_account.user_id = sys_user.id')
            ->join('inner join', 'auth_assignment', 'auth_assignment.user_id = sys_user.id')
            ->where(['sys_user.id' => $id])
            ->all();

        $s = array_column($user, 'store'); //获取关联商店id
        $s = array_unique($s);

        if (!$user){
            return false;
        }
        $session['user'] = $user; //将用户信息添加到session

        $store = Store::find() //查询商户关联的所有商店
            ->select('store_name as community_name, store_id as community_id')->where(['in', 'store_id', $s]);
        $community_name = $store->asArray()->all();
        $community_id = $store->orderBy('community_id')->indexBy('community_id')->column();

        //将用户信息贮存在会话中
        $session['community_id'] = $community_id; //用户关联小区，如 [55] => 明月园
        $session['community_name'] = $community_name; //用户关联小区数组，如[community_name] => 明月园
        $session['community'] = $s; //将用户关联的小区编码添加到session， 如[0] => 55

        return true;
    }
}
