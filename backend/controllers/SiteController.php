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
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
    }
	
    public function actionIndex()
    {
		$model = new Site;
		
//		echo '<pre />';
//		print_r($_SESSION['user']['0']);exit;

		$name = $_SESSION['user']['0']['name']; // 用户名
		$a = Yii::$app->request->userIP; //用户IP地址
		
        return $this->render('index',[
			'model' => $model,
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
