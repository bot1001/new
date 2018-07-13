<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LoginForm;
use common\models\UserAccount;
use common\models\UserData;
use common\models\Realestate;
use common\models\Community;
use common\models\UserRealestate;

class LoginController extends Controller
{
	//QQ登录
	public function actionQq()
	{
		echo '测试';
	}
	
	//微信登录或注册
	public function actionWx()
	{
		$code = $_GET['code'];
		$appid = 'wx6a6b40dfed3cf871';
		$secret = 'dedd7bad5b2b3c43a8e23597dfa27698';
		
		$output = Login::Wx($code,$appid, $secret); //返回数据
		$arr = json_decode($output, true); //数据转换
		
		$token = $arr['access_token'];
		$openid = $arr['openid'];
		
		$user = Login::Info($token, $openid); //返回用户信息
		
		$w_info = json_decode($user); //格式转换
		
		$o_p = UserAccount::find()
			->select('mobile_phone, account_id')
			->where(['account_id' => $openid])
			->asArray()
			->one();
		
		if($o_p)
		{
		     $relationship = UserRealestate::find()
				->select('realestate_id')
				->where(['in', 'account_id', $openid])
				->asArray()
				->all();
			
				if($relationship)
				{
					$r_id = array_column($relationship,'realestate_id');
		            $r_id = 5286;
					$reale = (new \yii\db\Query())->select('
					community_basic.community_name, community_building.building_name,community_realestate.room_number as number, community_realestate.room_name as name, community_realestate.realestate_id as id
					')
						->from('community_realestate')
						->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
						->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
						->where(['in', 'community_realestate.realestate_id', $r_id])
						//->limit(1)
						->all();
					
					//进入房屋选择界面
				    return $this->render('choice',['reale' => $reale, 'id' => $r_id]);
				}else{
					//否则进入房屋管理页面
					return $this->render('#');
				}
			
		}else{
			$account = new UserAccount();
		    $realestate = new Realestate();
			$data = new UserData();
			
		    $comm = Community::find()
		    	->select('community_id, community_name')
		    	->indexBy('community_id')
		    	->column();
			
			define("CAPTCHA_LEN", 36); // 随机数长度
            $Source = "0123456789abcdefghijklmnopqrstuvwxyz"; // 随机数字符源
			        
            $k = ""; // 随机数返回值
            for($i=0;$i<CAPTCHA_LEN;$i++){
                $n = rand(0, strlen($Source));
                if($n >= 36){
                    $n = 36 + ceil(($n - 36) / 3) * 3;
                    $k .= substr($Source, $n, 3);
                }else{
                    $k .= substr($Source, $n, 1);
                }
            }
		
		    return $this->render('/login/register', [
		    	'account' => $account, 
				'realestate' => $realestate,
				'k' => $k,
				'data' => $data,
				'comm' => $comm,
				'w_info' => $w_info
		    ]);
		}
	}
	
	// 微信扫码注册后转跳到这里
	public function actionNew()
	{
		$post = $_POST;
		$w_info = $_GET['w_info']; //接收微信账号信息
		
		$data = $post['UserData'];
		$realestate = $post['Realestate'];
		$account = $post['UserAccount'];
		
		$province = $data['province_id']; //接收省份ID
		$city = $data['city_id']; //接收城市ID
		$area = $data['area_id']; //接收县区ID
		$gender = $data['gender']; //接收性别
		$face = $data['face_path']; //接收图像地址
		
		$community = $realestate['community_id']; //接收小区ID
		$building = $realestate['building_id']; //接收楼宇ID
		$number = $realestate['room_number']; //接收单元
		$name = $realestate['room_name']; //接收房号
		$mobile = $realestate['phone']; //接收验证手机号码
		$nick = $realestate['owners_name']; //接收用户昵称
		
		$user_name = $account['user_name']; //接收用户昵称
		$phone = $account['mobile_phone']; //接收登录手机号码
		$p = $account['password'];
		$password = md5($p); //接收并加密密码
		$weixin_openid = $account['weixin_openid']; //接收微信用户的openID
		$account_id = $account['account_id']; //接收用户ID（程序随机生成）
		$unionid = $account['wx_unionid']; //接收微信唯一编码（暂时存着）
		
		$nickname = $w_info['nickname'] ; //用户昵称
		
		//验证注册手机号码是否存在
		$u_account = UserAccount::find()
			->where(['mobile_phone' => "$phone", 'account_role' => '0'])
			->asArray()
			->one();
		
		if($u_account){ //如果存在则绑定微信账号
			$result = UserAccount::updateAll(['weixin_openid' => $weixin_openid, 'wx_unionid' => $unionid],
											  'account_id = :a_id', [':a_id' => $u_account['account_id']]);
			$u_data = UserData::updateAll(['face_path' => $face], 'account_id = :a_id', [':a_id' => $u_account['account_id']]);
			
			//如果修改成功则自动登录
			if($result || $u_data){
                $user = \frontend\models\User::find()->where(['mobile_phone'=> "$phone"])->asArray()->one();
                \frontend\models\Site::saveMessage($user, $w_info);
				return $this->render('/site/index');
			}
		}else{
		    if(empty($user_name)){
		    	$user_name == $nickname;
		    }
			
		    $transaction = Yii::$app->db->beginTransaction();
		    try{
		        $account = new UserAccount(); //实例化模型
		        
		        //模型块赋值
		        $account->account_id = $account_id;
		        $account->user_name = $nick;
		        $account->password = $password;
		        $account->new_pd = $password;
		        $account->mobile_phone = $phone;
		        $account->weixin_openid = $weixin_openid;
		        $account->wx_unionid = $unionid;
		        $account->new_message = '0';
		        $account->status = '1';
		    
		        $yes = $account->save(); // 保存
		    	
                $id = Yii::$app->db->getLastInsertID(); //最新插入的数据ID
		    	
		        $ship = new UserRealestate();
		        $ship->account_id =  $account_id;
		        $ship->realestate_id = $name;
		    	
		        $r = $ship->save(); //保存用户关联信息
		    		
		    	$userdata = new UserData();
		    	
		    	$userdata->account_id = $account_id;
		    	$userdata->real_name = $nick;
		    	$userdata->gender = $gender;
		    	$userdata->face_path = $face;
		    	$userdata->province_id = $province;
		    	$userdata->city_id = $city;
		    	$userdata->area_id = $area;
		    	$userdata->nickname = $nick;
		    	
		    	$u = $userdata->save(); //保存用户资料

		    	if($yes && $r && $u){
		    		$user = \frontend\models\User::find()->where(['in', 'user_id', $id])->asArray()->one();
		    						
		    		\frontend\models\Site::saveMessage($user, $w_info);
		    	}
				$transaction->commit();
		    }catch(\Exception $e){
		    	$transaction->rollback();
		    }
		    
		    if(isset($user)){
		    	return $this->render('/site/index');
		    }
		}
		return $this->redirect(['/login/login']);//如果以上操作失败，则自动转跳到登录页面
	}

	//用户登录
	public function actionLogin()
    {
		$this->layout = 'login'; //设置模板
		
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
		
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
		{
			$post = $_POST['LoginForm'];
			$phone = $post['mobile_phone'];
			
			\frontend\models\Site::saveLogin($phone);
			
            return $this->goBack();
        } else {
            return $this->render('l', [
                'model' => $model,
            ]);
        }
    }
}
