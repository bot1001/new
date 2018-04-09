<?php

namespace backend\controllers;

use Yii;
use app\models\TicketBasic;
use app\models\TicketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Information;

/**
 * TicketController implements the CRUD actions for TicketBasic model.
 */
class TicketController extends Controller
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

    /**
     * Lists all TicketBasic models.
     * @return mixed
     */
    public function actionIndex()
    {
		$get = $_GET;//接收专递过来的参数
        $searchModel = new TicketSearch();//实例化模型
        
		//判断是否有信息栏传过来的参数
		if(isset($get['community']) && isset($get['building']))
		{
			$searchModel->community_name = $get['community'];
			$searchModel->building_name = $get['building'];
			$searchModel->name = '待接单';
			if($_SESSION['user']['community']){
				Information::updateAll(['reading' => 1], 'community = :c', [':c' => $get['c']]);
			}
		}elseif(isset($get['name'])){
			$searchModel->name = '待接单';
			if($_SESSION['user']['community']){
				Information::updateAll(['reading' => 1], 'community = :c', [':c' => $get['c']]);
			}
		}
		
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
     * Displays a single TicketBasic model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = new TicketBasic();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TicketBasic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TicketBasic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ticket_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TicketBasic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ticket_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TicketBasic model.
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
     * Finds the TicketBasic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketBasic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketBasic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
