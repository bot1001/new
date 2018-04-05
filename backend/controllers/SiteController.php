<?php

namespace backend\controllers;

use Yii;
use app\models\Site;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SysUser;
use app\models\CommunityBasic;

class SiteController extends Controller
{
	//public $layout = 'pm';
	//public $layout = false;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
	//检查用户是否登录
	/*public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
    }*/
	
	public function actionTest()
	{
		$code = $_GET['code'];
		$appid = 'wx6a6b40dfed3cf871';
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=dedd7bad5b2b3c43a8e23597dfa27698&code=$code&grant_type=authorization_code";
		
		$ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        
        // 4. 释放curl句柄
        curl_close($ch);
		$arr = json_decode($output, true);
		$token = $arr['access_token'];
		$opentid = $arr['openid'];
		
		$user_info = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$opentid&lang=zh_CN";
		
		$u = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL,$user_info);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($u,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $user = curl_exec($u);
        
        // 4. 释放curl句柄
        curl_close($u);
		$a = json_decode($user);
		echo '<pre>';
		print_r($a);
	}
	
    public function actionIndex()
    {
		$model = new Site;
		
		$session = Yii::$app->session;
		$post = Yii::$app->user->identity;
		foreach($post as $info)
		$session['user'] = $post;
		$name = $post['name']; // 用户名
		$a = Yii::$app->request->userIP; //用户IP地址
				
        return $this->render('index',[
			'model' => $model,
			'post' => $post,
			'name' => $name,
			'a' => $a,
		]);
    }
	//切换小区
	public function actionChange()
	{
		echo '你好！';
	}
    public function actionLogin()
    {
		$this->layout = 'main1';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        /*Yii::$app->session->removeAll();
        if(!isset(Yii::$app->session['user']['islogin'])){
           $this->reditect(['site/login']);
           Yii::$app->end();
        }
        $this->goback();*/
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
