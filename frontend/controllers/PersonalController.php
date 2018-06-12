<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Community;
use common\models\Realestate;
use common\models\UserData;
use common\models\UserRealestate;

class PersonalController extends Controller
{
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login/login']);
            return false;
        }
        return true;
    }
	
	/**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	public function actionIndex()
	{
		return $this->render('index');
	}
	
	//添加新房屋
	function actionCreate()
	{
		$company = Community::find()
			->select('community_name as community, community_id as id')
			->indexBy('community')
			->column();
		
		$province = \common\models\Area::getProvince();
		$realestate = new Realestate();
		$data = new UserData();
		
		if($_POST){
			$post = $_POST['Realestate'];
			
			$realestate_id = $post['room_name'];
			$account_id = $_SESSION['user']['account_id'];
			$phone = $_SESSION['user']['mobile_phone'];
			
			$ship = new UserRealestate(); //实例化用户关联模型
		    $ship->account_id = $account_id; //赋值用户ID
		    $ship->realestate_id = $realestate_id; //赋值关联房屋ID
			
			$r = $ship->save(); //保存用户关联信息
			
			if($r){
				\frontend\models\Site::saveLogin($phone); //变更用户房屋信息
				return $this->render('index');
			}else{
				return $this->redirect( Yii::$app->request->referrer);
			}
		}
		
		return $this->render('create', ['province' => $province, 'data' => $data, 'realestate'=> $realestate,'community' => $company]);
	}
	
	//解绑房屋
	function actionDelete($id,$k)
	{
		$account_id = $_SESSION['user']['account_id']; //获取用户ID
		
		$ship = UserRealestate::findOne(['realestate_id' => "$id"])->delete(); //删除
		
		if($ship){
			unset($_SESSION['house'][$k]);
		}
		
		return $this->redirect( Yii::$app->request->referrer);
	}
}