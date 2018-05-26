<?php

namespace backend\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SysCommunity;
use app\models\SysUser;
use app\models\CommunityBasic;

class LoginController extends Controller
{
    //public $layout = 'main2';
    
    public function actionIndex()
    {
        {
		$this->layout = 'main1';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
		{
			$session = Yii::$app->session;
		    $post = Yii::$app->user->identity; //获取用户信息
			
			$id = $post['id']; //获取用户序号
			
			$user = (new \yii\db\Query())->select('
			         sys_user.id as id, 
			         company.name as company,
			         sys_user.name as name, 
			         sys_user.role as role, 
			         sys_user.phone as phone, 
			         sys_user.comment as comment, 
			         sys_user.create_id as create, 
			         sys_user.create_time as create_time,
			         auth_assignment.item_name as Role')
			    ->from('sys_user')
			    ->join('inner join', 'company', 'company.id = sys_user.company')
			    ->join('inner join', 'auth_assignment', 'auth_assignment.user_id = sys_user.id')
				->where(['sys_user.id' => $id])
				->all();
			
			$session['user'] = $user; //将用户信息添加到session
			if(empty($user)){
				return $this->redirect(['/site/logout']); 
			}
			//获取用户绑定小区
			$syscommuntiy = SysCommunity::find()
	            	->select('community_id')
				    ->where(['sys_user_id' => $session['user']['0']['id']])
	            	->asArray()
	            	->one();
			//拆分用户关联小区
	        $s = explode(',',$syscommuntiy['community_id']);
			
			//获取关联小区名称
			$community = CommunityBasic::find()
				->select('community_name, community_id')
				->where(['in', 'community_id', $s]);
			
			$community_name = $community->asArray()->all();
			
			$community_id = $community->orderBy('community_id')->indexBy('community_id')->column();
			
			$session['community_id'] = $community_id;
			$session['community_name'] = $community_name;
			$session['community'] = $s; //将用户关联的小区添加到session
			
            return $this->goBack();
        }
		
        return $this->render('index', [
            'model' => $model,
        ]);
       }
    }
	//QQ登录
	public function actionQQ()
	{
		
	}
}
