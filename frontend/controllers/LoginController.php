<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use frontend\models\User;
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
		
		$output = Login::Wx($code,$appid); //返回数据
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
		    $community = Community::find()
		    	->select('community_id, community_name')
		    	->asArray()
		    	->all();
		    
		    $comm = ArrayHelper::map($community, 'community_id', 'community_name');
		
		    return $this->render('/login/login', [
		    	'account' => $account, 
				'realestate' => $realestate,
				'comm' => $comm,
				'w_info' => $w_info
		    ]);
		}
	}
	
	public function actionNew()
	{
		$post = $_POST;
		$info = $_GET['w_info'];
		
		$account_id = $info['openid']; //接收微信用户的openID
		$nickname = $info['nickname'] ; //用户昵称
		$r_id = $post['Realestate']['room_number'];	//房屋编号	
		$phone = $post['UserAccount']['mobile_phone']; //接收手机号码
		$password = $post['UserAccount']['password']; //接收密码（明文)
		$password = md5($password); //md5加密明文密码
				
		$r_p = Realestate::find()
			->select('owners_cellphone')
			->where(['realestate_id' => $r_id])
			->asArray()
			->one();
		
		if($phone === $r_p['owners_cellphone'])
		{
			$account = new UserAccount(); //实例化模型
	
		    
		    //模型块赋值
		    $account->account_id = $account_id;
		    $account->mobile_phone = $phone;
		    $account->password = $password;
		    $account->user_name = $nickname;
		    $account->account_role = '0';
		    $account->new_message = '0';
		    $account->status = '1';
		    
		    $e = $account->save(); // 保存
    
		    if($e){
		    	$ship = new UserRealestate();
		    	
		    	$ship->account_id =  $account_id;
		    	$ship->realestate_id = $r_id;
		    	$r = $ship->save();
		    }
		    
		    return $this->render('#', ['id' => $r_id]);
		}else{
			echo '<script>alert("信息校验失败，请检查数据！")</script>';
			//echo "<script>alert('打印数据为空，请确认订单信息！')</script>";
			//return $this->redirect(Yii::$app->request->referrer);
		}
	
		
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
        $this->layout = 'main'; 

        $model = new User();
        if ($model->load(Yii::$app->request->post())&& $model->login()) 
		{			
			print_r($_POST);
//            return $this->goBack();
			Yii::$app->end();
        } else {
            return $this->render('l', [
                'model' => $model,
            ]);
        }
    }
}
