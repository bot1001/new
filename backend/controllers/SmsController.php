<?php

namespace backend\controllers;

use Yii;
use app\models\Sms;
use app\models\SmsSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends Controller
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

    //GridView页面直接编辑
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'sms' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Sms::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                },
                'ajaxOnly' => true,
            ]
        ]);
    }

    /**
     * Lists all Sms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsSearch();

        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $searchModel->id = $id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sms model.
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
     * Creates a new Sms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sms model.
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
     * Finds the Sms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sms::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
