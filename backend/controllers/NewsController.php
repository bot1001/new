<?php

namespace backend\controllers;

use app\models\CommunityBasic;
use Yii;
use app\models\CommunityNews;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use app\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for CommunityNews model.
 */
class NewsController extends Controller
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
     * Lists all CommunityNews models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $comm = CommunityBasic::community(); //从模型中获取小区数组

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'comm' => $comm
        ]);
    }
	
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'news' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => CommunityNews::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }

    /**
     * Displays a single CommunityNews model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(isset($_GET['note'])){
            return $this->renderAjax('v', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }

    }
	public function actionView1($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CommunityNews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CommunityNews();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CommunityNews model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CommunityNews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CommunityNews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommunityNews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommunityNews::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
