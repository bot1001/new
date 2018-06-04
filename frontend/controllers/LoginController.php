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
	
	public function actionNew()
	{
		$post = $_POST;
		$info = $_GET['w_info']; //接收微信账号信息
		
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
		$phone = $realestate['phone']; //接收验证手机号码
		$nick = $realestate['owners_name']; //接收用户昵称
		
		$user_name = $account['user_name']; //接收用户昵称
		$mobile = $account['mobile_phone']; //接收登录手机号码
		$password = md5($account['password']); //接收并加密密码
		$weixin_openid = $account['weixin_openid']; //接收微信用户的openID
		$account_id = $account['account_id']; //接收用户ID（程序随机生成）
		$unionid = $account['wx_unionid']; //接收微信唯一编码（暂时存着）
		
		$nickname = $info['nickname'] ; //用户昵称
		
		if(empty($nick)){
			$nick == $nickname;
		}
				
//		echo '<pre >';
//		print_r($r_p);
		exit;
		
		$account = new UserAccount(); //实例化模型
		
		//模型块赋值
		$account->account_id = $account_id;
		$account->user_name = $nick;
		$account->password = $password;
		$account->mobile_phone = $phone;
		$account->weixin_openid = $weixin_openid;
		$account->wx_unionid = $unionid;
		$account->new_message = '0';
		$account->status = '1';
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
		    $yes = $account->save(); // 保存
            
		    if($yes){
		    	$ship = new UserRealestate();
		    	$ship->account_id =  $account_id;
		    	$ship->realestate_id = $name;
		    	$r = $ship->save();
				
				if($r){
					$userdata = new UserData();
					
					$userdata->account_id = $account_id;
					$userdata->real_name = $nick;
					$userdata->gender = $gender;
					$userdata->face_path = $face;
					$userdata->province_id = $province;
					$userdata->city_id = $city;
					$userdata->area_id = $area;
					$userdata->nickname = $nick;
					
					$u = $userdata->save();
					if($u){
						$transaction->commit();
					}
				}else{
					$transaction->rollback();
				}
		    }else{
				$transaction->rollback();
			}
		    $transaction->commit();
		}catch(\Exception $e){
			echo '<pre>';
			print_r($e);
		}
		return $this->redirect('/site/index');
	}
	
	//裕家人开放平台授权回调地址
	public function actionIndex()
	{
		return $this->render('index');
	}
	
	//裕家人开放平台授权回调测试
	public function actionTest()
	{
		echo '<pre >';
		print_r($_GET);
	}
	
	//用户登录
	public function actionLogin()
    {
		$this->layout = 'login';
		
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('l', [
                'model' => $model,
            ]);
        }
    }
	
	//用户退出
	public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
