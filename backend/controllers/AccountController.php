<?php

namespace backend\controllers;

use Yii;
use app\models\UserAccount;
use app\models\UserAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\WorkR;
use app\models\UserData;

/**
 * AccountController implements the CRUD actions for UserAccount model.
 */
class AccountController extends Controller
{
	public $layout = "m2";
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
	
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
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
		$this ->layout = "m2";
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
			    	$userdata->save();
			    }
				$transaction->commit();
			}catch(Exception $e){
				$transaction->rollBack();
			}
            return $this->redirect(['/account/view', 'id' => $id]);
        } else {
            return $this->render('create', [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
}
