<?php

namespace backend\controllers;

use Yii;
use common\models\Accumulate;
use common\models\AccumulateSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccumulateController implements the CRUD actions for Accumulate model.
 */
class AccumulateController extends Controller
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
     * Lists all Accumulate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccumulateSearch();
        if(isset($_GET['account_id'])){ //判断是否是来自积分列表的查询
            $account_id = $_GET['account_id'];
            $searchModel->account_id = $account_id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //用户积分和
    function actionAccumulate()
    {
        $name = '';
        if(isset($_GET['name'])){
            $name = $_GET['name'];
        }

        $from = '';
        if(isset($_GET['from'])){
            $from = $_GET['from'];
        }

        $to = '';
        if(isset($_GET['to'])){
            $to = $_GET['to'];
        }

        $searchModel = new \app\models\AccumulateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //获取积分综合
        $amount = (new Query())->select('sum(amount) as amount')->from('store_accumulate')->one();

        return $this->render('sum',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'amount' => $amount['amount'],
                'name' => $name,
                'from' => $from,
                'to' => $to
            ]);
    }

    /**
     * Displays a single Accumulate model.
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
     * Creates a new Accumulate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accumulate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Accumulate model.
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
     * Deletes an existing Accumulate model.
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
     * Finds the Accumulate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accumulate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accumulate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
