<?php

namespace backend\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SysCommunity;

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
			
			$session['user'] = $post; //将用户信息添加到session
			
			//获取用户绑定小区
			$syscommuntiy = SysCommunity::find()
	            	->select('community_id')
				    ->where(['sys_user_id' => $session['user']['id']])
	            	->asArray()
	            	->one();
			//拆分用户关联小区
	        $s = explode(',',$syscommuntiy['community_id']);

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
