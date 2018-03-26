<?php

namespace backend\controllers;

use Yii;
use app\models\UserRelationshipRealestate;
use app\models\CommunityRealestate;
use app\models\RelationshipSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RelationshipController implements the CRUD actions for UserRelationshipRealestate model.
 */
class RelationshipController extends Controller
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

    /**
     * Lists all UserRelationshipRealestate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelationshipSearch();
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
     * Displays a single UserRelationshipRealestate model.
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
     * Creates a new UserRelationshipRealestate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($account_id,$mobile_phone)
    {
        $model = new UserRelationshipRealestate();
		$model ->account_id = $account_id;
		$realestate = CommunityRealestate::find()->where(['owners_cellphone' => $mobile_phone])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'realestate' => $realestate,
            ]);
        }
    }

    /**
     * Updates an existing UserRelationshipRealestate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$realestate = CommunityRealestate::find()->where(['realestate_id' => $model->realestate_id])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'realestate' => $realestate,
            ]);
        }
    }

    /**
     * Deletes an existing UserRelationshipRealestate model.
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
     * Finds the UserRelationshipRealestate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserRelationshipRealestate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserRelationshipRealestate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
