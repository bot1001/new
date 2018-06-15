<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use frontend\models\Login;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Site extends ActiveRecord
{
	
    public static function getK()
	{
       $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $k = "";
       for($i=0;$i<32;$i++)
        {
            $k .= $str{mt_rand(0,32)}; //生成php随机数
        }
        return $k;
    }
	
	//微信扫码登录信息保存函数
	public static function saveMessage($user, $w_info)
	{
		$info = \frontend\models\User::findOne(['in', 'user_id', $user['user_id']]);
		Yii::$app->user->login($info);

		$info_m = (new \yii\db\Query)
			->select('user_account.*, user_data.*')
			->from('user_account')
			->join('inner join', 'user_data', 'user_data.account_id = user_account.account_id')
			->where(['in', 'user_account.account_id', $user['account_id']])
			->one();
		
		//获取关联房屋信息
		$house = (new \yii\db\Query)
			->select('community_realestate.room_number as number, community_realestate.room_name as room, 
			community_realestate.owners_name as name, community_realestate.owners_cellphone as phone,
			community_realestate.acreage as acreage, community_realestate.finish as finish,
			community_realestate.inherit as inherit, community_realestate.decoration as decoration,
			community_realestate.commencement as commencement, community_realestate.delivery as delivery,
			community_realestate.orientation as orientation, community_realestate.property as property,
			community_basic.community_name as community,community_basic.community_id as community_id, 
			community_building.building_id as building_id,community_building.building_name as building,
			user_relationship_realestate.realestate_id as id, community_realestate.acreage as acreage')
			->from('community_realestate')
			->join('inner join', 'user_relationship_realestate', 'user_relationship_realestate.realestate_id = community_realestate.realestate_id')
			->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
			->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
			->where(['in', 'user_relationship_realestate.account_id', $user['account_id']])
			->all();
		
		$_SESSION['w_info'] = $w_info; //保存微信信息到session
		$_SESSION['user'] = $info_m; //保存用户信息到session
		$_SESSION['home'] = reset($house); //默认进入第一套房子
		$_SESSION['house'] = $house; //保存关联房屋到session
	}
	
	//微信扫码之后获取用户信息函数
	public static function getMessage($get)
	{
		$code = $get['code']; //获取登录返回的code
		$config = Yii::$app->params['wx_open'];
		$appid = $config['appid'];  //开发平台应用 APPID
		$secret = $config['secret']; //开放平台应用秘钥
		$output = Login::Wx($code,$appid, $secret); //调用login类中封装好的模拟连接
		$arr = json_decode($output, true); //将json数组转换成普通数组
		
		$token = $arr['access_token']; //提取access_token
		$openid = $arr['openid']; //提取openID
		
		$info = Login::Info($token, $openid); //查询登录用户信息
		$w_info = json_decode($info, true); //将json数据转换普通数组
		
		return $w_info;
	}
	
	//账户密码登录保存信息到session函数
	public static function saveLogin($phone)
	{
		$user = (new \yii\db\Query)
			->select('user_account.*, user_data.*')
			->from('user_account')
			->join('inner join', 'user_data', 'user_data.account_id = user_account.account_id')
			->where(['in', 'user_account.mobile_phone', $phone])
			->one();
		
		//获取关联房屋信息
		$house = (new \yii\db\Query)
			->select('community_realestate.room_number as number,community_realestate.room_name as room, 
			community_realestate.owners_name as name, community_realestate.owners_cellphone as phone,
			community_realestate.acreage as acreage, community_realestate.finish as finish,
			community_realestate.inherit as inherit, community_realestate.decoration as decoration,
			community_realestate.commencement as commencement, community_realestate.delivery as delivery,
			community_realestate.orientation as orientation, community_realestate.property as property,
			community_basic.community_name as community,community_basic.community_id as community_id, 
			community_building.building_id as building_id,community_building.building_name as building,
			user_relationship_realestate.realestate_id as id, community_realestate.acreage as acreage')
			->from('community_realestate')
			->join('inner join', 'user_relationship_realestate', 'user_relationship_realestate.realestate_id = community_realestate.realestate_id')
			->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
			->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
			->where(['in', 'user_relationship_realestate.account_id', $user['account_id']])
			->all();
		
		
		if(!isset($_SESSION['user'])){
			$_SESSION['user'] = $user; //保存用户信息到session
		}
		
		$info = \frontend\models\User::find()->where(['in', 'mobile_phone', $phone])->One(); //查询登录用户信息
		Yii::$app->user->login($info); //保存用户信息到登录程序
		
		$_SESSION['house'] = $house; //保存关联房屋到session
		$_SESSION['home'] = reset($house); //默认进入第一套房子
		
	}
}
