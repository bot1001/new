<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use common\models\CommunityFees;
use common\models\CommunityFeesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * CommunityFeesController implements the CRUD actions for CommunityFees model.
 */
class CommunityFeesController extends Controller
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

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/img/fees/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径、广告
                    "imageMaxSize" => 512000,
                ],
            ],
            'fees' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => CommunityFees::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                },
                'ajaxOnly' => true,
            ],
        ]);
    }

    /**
     * Lists all CommunityFees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommunityFeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CommunityFees model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = (new Query())
            ->select('community_fees.*, sys_user.name, community_basic.community_name as community')
            ->from('community_fees')
            ->join('inner join', 'sys_user', 'sys_user.id = community_fees.author')
            ->join('inner join', 'community_basic', 'community_fees.community_id = community_basic.community_id')
            ->where(['community_fees.id' => "$id"])
            ->one();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CommunityFees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CommunityFees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CommunityFees model.
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
     * Deletes an existing CommunityFees model.
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
     * Finds the CommunityFees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommunityFees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommunityFees::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
