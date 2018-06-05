<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Login;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\UserAccount;
use common\models\Community;
use common\models\Realestate;
use common\models\UserData;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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

    /**
     * @inheritdoc
     */
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
            $this->redirect(['/login/login']);
            return false;
        }
        return true;
    }*/

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
		$get = $_GET;
		if(isset($get['code'])){
			$code = $get['code']; //获取登录返回的code
		    $appid = 'wx61eec3717a800533';  //开发平台应用 APPID
		    $secret = '1cb3e3afab132b9d1d4777e9f27cdbf2'; //开放平台应用秘钥
		    $output = Login::Wx($code,$appid, $secret); //调用login类中封装好的模拟连接
		    $arr = json_decode($output, true); //将json数组转换成普通数组
		    
		    $token = $arr['access_token']; //提取access_token
		    $openid = $arr['openid']; //提取openID
		    
		    $info = Login::Info($token, $openid); //查询登录用户信息
			$w_info = json_decode($info, true); //将json数据转换普通数组
						
			$openid = $w_info['openid']; //提取openID
			
			$user = UserAccount::find() //查询用户是否存在
				->where(['in', 'weixin_openid', $openid])
				->asArray()
				->one();
						
			if($user){
				$model = new \frontend\models\LoginForm();
				$phone = $user['mobile_phone'];
				$p = $user['password'];
				
				return $this->render('/login/index', ['model' => $model, 'phone' => $phone, 'password' => $p]);
			}else{
				$k = \frontend\models\Site::getK(); //获取程序生成account_id
				$comm = Community::find() //获取小区
		    	           ->select('community_name, community_id')
		    	           ->indexBy('community_id')
		    	           ->column();
				
				$province = \common\models\Area::getProvince();
				
				$account = new UserAccount();
		        $realestate = new Realestate();
		        $data = new UserData();
				
				return $this->render('register', [
		        	'account' => $account, 
			    	'realestate' => $realestate,
			    	'k' => $k,
			    	'data' => $data,
					'province' => $province,
			    	'comm' => $comm,
			    	'w_info' => $w_info
		        ]);
			}  
		}else{
			return $this->render('index');
		}	 
    }
	
	public function actionLoad()
    {
		$this->layout = 'home';
        return $this->render('load');
    }
	
	public function actionPhone()
    {
		
		foreach($_POST as $key => $post);
	    $info = explode(',', $key);
		
		$p =  Realestate::find()
		    ->andwhere(['owners_cellphone' => reset($info)])
			->andwhere(['in', 'realestate_id', $info['1']])
		    ->andwhere(['owners_name' => end($info)])
		    ->asArray()
		    ->one();
		
		if($p){
			return true;
		}else{
			return false;
		}
    }
	
	//安卓版下载
	public function actionAndroid()
	{
		return \Yii::$app->response->sendFile('./files/yuda.apk');
	}
	
	//物业端下载
	public function actionYuda()
	{
		return \Yii::$app->response->sendFile('./files/wyd.apk');
	}

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
