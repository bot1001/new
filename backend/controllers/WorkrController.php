<?php

namespace backend\controllers;

use Yii;
use app\models\WorkR;
use app\models\WorkRSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\user_account;
use app\models\Company;
use app\models\UserAccount;
use app\models\CommunityBasic;

/**
 * WorkRController implements the CRUD actions for WorkR model.
 */
class WorkrController extends Controller
{
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

    /**
     * Lists all WorkR models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkRSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			$searchModel->account_id = $id;
		}
		//获取小区
		$community = CommunityBasic::find()
			->select('community_name, community_id')
			->orderBy('community_name')
			->indexBy('community_id')
			->column();
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'community' => $community
        ]);
    }

    /**
     * Displays a single WorkR model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorkR model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkR();
		
		//获取小区
		$company = Company::find()->select('name, id')->orderBy('name')->indexBy('id')->column();
		
		//获取用户
		if(isset($_GET['user_id']))
		{
			$id = $_GET['user_id'];
			$user = UserAccount::find()
		    	->select('user_name, account_id')
		    	->andwhere(['account_role' => '1', 'user_id' => "$id"])
		    	->orderBy('user_name DESC')
		    	->indexBy('account_id')
		    	->column();
		}else{
		    $user = UserAccount::find()
		    	->select('user_name, account_id')
		    	->where(['account_role' => '1'])
		    	->orderBy('user_name DESC')
		    	->indexBy('account_id')
		    	->column();
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
			'company' => $company,
			'user' => $user
        ]);
    }

    /**
     * Updates an existing WorkR model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WorkR model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		$this->findModel($id)->delete();
		
		return $this->redirect( Yii::$app->request->referrer );//返回请求页面
    }
	
	//批量删除
	public function actionDel()
    {
		foreach ( $_POST['ids'] as $id ) 
		{
			if($id == ''){
				$session = Yii::$app->session;
				$session->setFlash('fail','1');
			}else{
				$this->findModel( $id )->delete();
			}
		}
		
		return $this->redirect( Yii::$app->request->referrer );//返回请求页面
    }

    /**
     * Finds the WorkR model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkR the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkR::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
