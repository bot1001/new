<?php

namespace backend\controllers;

use Yii;
use app\models\UserAccount;
use app\models\UserAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UserData;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;

/**
 * AccountController implements the CRUD actions for UserAccount model.
 */
class AccountController extends Controller
{
    /**
     * @inheritdoc
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
	
	//GridView 页面直接编辑代码
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'account' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => UserAccount::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }

    /**
     * Lists all UserAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'k' => $k,
        ]);
    }

    /**
     * Displays a single UserAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($a)
    {
        $model = new UserAccount();
		$userdata = new UserData();
		$a = mb_substr($a,0,32);

        if (isset($_POST['UserAccount'])) 
		{
			$account = $_POST['UserAccount']; //接收用户数据
			$user = $_POST['UserData']; //接收用户数据
			
			//模型块赋值
			$a = $_GET['a'];
			$name = $account['user_name'];
			$password = md5($account['password']);
			$mobile_phone = $account['mobile_phone'];
			
			//查找是否存在此用户 标志信息为手机号码和用户姓名
			$useraccount = UserAccount::find()
				->andwhere(['user_name' => $name, 'mobile_phone' => $mobile_phone])
				->asArray()
				->all();
			
			//如果不存在
			if(empty($useraccount)){
				$sql = "insert ignore into user_account(account_id, user_name, password, mobile_phone, account_role)
			    values ('$a', '$name', '$password','$mobile_phone', '1')";
			    
			    $transaction = Yii::$app->db->beginTransaction();   //开始事务
			    try{
  		            $m = Yii::$app->db->createCommand( $sql )->execute();
			    	$id = Yii::$app->db->getLastInsertID();
			        if($m){
			        	$userdata->account_id = $_GET['a'];
			        	$userdata->gender = $user['gender'];
			        	$userdata->real_name = $account['user_name'];
			        	$d = $userdata->save();
			        }
			    	$transaction->commit(); //提交事务
			    }catch(Exception $e){
			    	$transaction->rollBack(); //数据库回滚
			    }
			    return $this->redirect(['index']);
			}else{
				return $this->redirect( Yii::$app->request->referrer);
			}
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
				'a' => $a,
				'userdata' => $userdata
            ]);
        }
    }
	
	public function actionCreate1($account_id)
    {
		
        $model = new UserAccount();
		$model ->account_id = $account_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $account_id = $model['account_id'];
		$userdata = $this->findData($account_id);

        if ($model->load(Yii::$app->request->post())) {
            $account = $_POST['UserAccount'];
            $model->password = md5($account['password']);

            $model->save(); //保存数据

            $user = $_POST['UserData'];
            $gender = $user['gender'];
            $u = $userdata::updateAll(['gender' => $gender], 'account_id = :aid', [':aid' => $account_id]);

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
				'userdata' => $userdata
            ]);
        }
    }

    /**
     * Deletes an existing UserAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //查找用户信息
    protected  function findData($account_id)
    {
        if (($model = UserData::findOne(['account_id' => $account_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
