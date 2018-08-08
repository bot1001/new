<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UserAccount;
use common\models\Community;
use common\models\Realestate;
use common\models\UserData;
use common\models\HouseInfo;

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
	/*public function beforeAction($action)
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
		if(isset($get['code']))
		{
			$w_info = \frontend\models\Site::getMessage($get);
			if(empty($w_info)){
				return $this->redirect(['/login/login']);
			}
				
			$unionid = $w_info['unionid']; //提取openID

			$user = (new \yii\db\Query())//查询用户是否存在
                ->from('user_account')
                ->join('inner join', 'user_openid', 'user_account.account_id = user_openid.account_id')
                ->where(['user_account.wx_unionid' => "$unionid"])
                ->one();

			if($user){
				\frontend\models\Site::saveMessage($user, $w_info);
				
				return $this->render('index');
			}else{
				$k = \frontend\models\Site::getK(); //获取程序生成account_id
				
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
		//验证用户注册时资料
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
			$house = HouseInfo::find()
			->andwhere(['phone' => reset($info)])
			->andwhere(['in', 'realestate', $info['1']])
		    ->andwhere(['name' => end($info)])
		    ->asArray()
		    ->one();
			
			if($house){
				return true;
			}
		}
		return false;
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
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
