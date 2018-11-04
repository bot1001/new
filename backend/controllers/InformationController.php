<?php

namespace backend\controllers;

use Yii;
use common\models\Information;
use app\models\InformationSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InformationController implements the CRUD actions for Information model.
 */
class InformationController extends Controller
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
     * Lists all Information models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InformationSearch();
        if(isset($_GET['type'])) //判断是否存在传值
        {
            $searchModel->type = $_GET['type'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //系统消息列表
    function actionList()
    {
        $searchModel = new InformationSearch();
        $searchModel->reading = '0';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->setPageSize(8); //设置每页获取记录数量
        $dataProvider->query->where(['in', 'type', ['2', '3']]);

        $wuye = $searchModel->search(Yii::$app->request->queryParams);
        $wuye->query->where(['type' => '1']);
        $wuye->pagination->setPageSize(8); //设置每页获取记录数量

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'wuye' => $wuye
        ]);
    }

    /**
     * Displays a single Information model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $type)
    {
        Information::updateAll(['reading' => '1'], 'remind_id = :o_id', [':o_id' => "$id"]);
        if($type == '2' || $type == '3')
        {
            $model = (new Query())
                ->select('information.remind_id as id, store_basic.store_name as name, information.detail,
                information.times, information.reading, information.ticket_number as number,
                from_unixtime(information.remind_time) as time,
                information.property, sys_user.name as user_name')
                ->from('information')
                ->join('inner join', 'store_basic', 'store_basic.store_id = information.community')
                ->join('inner join', 'sys_user', 'sys_user.id = information.target')
                ->where(['information.remind_id' => "$id"])
                ->one();
        }else{
            $model = (new Query())
                ->select('community_basic.community_name as name, information.detail,
                information.times, information.reading, information.ticket_number, 
                from_unixtime(information.remind_time) as time,
                information.property, sys_user.name as user_name')
                ->from('information')
                ->join('inner join', 'community_basic', 'community_basic.community_id = information.community')
                ->join('inner join', 'sys_user', 'sys_user.id = information.target')
                ->where(['information.remind_id' => "$id"])
                ->one();
        }

        return $this->render('view', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * Creates a new Information model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Information();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->remind_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Information model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->remind_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Information model.
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
     * Finds the Information model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Information the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Information::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
