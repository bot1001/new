<?php

namespace backend\controllers;

use backend\models\Login;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SysCommunity;
use app\models\CommunityBasic;

class LoginController extends Controller
{
    //public $layout = 'main2';
    
    public function actionIndex()
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
            $type = $post->salt; //用户类型，0=>管理员，1=>物业账户,2=>商户

            if($type == '1'){ //判断用户类型
                $login = Login::wuye($id, $session); //执行登陆成功后的操作
            }elseif($type == '2'){
                $login = Login::market($id, $session);
            }elseif($type == '0'){
                $login = Login::manager($id, $session);
            }else{
                $this->redirect(['/site/logout']);
            }

            if($login){ //如果登陆成功则返回
                return $this->goBack();
            }
            return $this->redirect(['/site/logout']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
	//QQ登录
	public function actionQQ()
	{
		
	}
}
