<?php

namespace backend\controllers;

use common\models\Order;
use common\models\Products;
use Yii;
use common\models\Recharge;
use app\models\RechargeSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;

/**
 * RechargeController implements the CRUD actions for Recharge model.
 */
class RechargeController extends Controller
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
     * Lists all Recharge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RechargeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //gridview 页面直接编辑
    function actions()
    {
        return ArrayHelper::merge(parent::actions(),[
            'recharge' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => Recharge::className(),
                'outputValue' => function($model, $atrribute, $key, $indx){
                },
                'ajaxOnly' => true
            ]
        ]);
    }

    /**
     * Displays a single Recharge model.
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

    //添加充值订单
    function actionAdd($realestate)
    {
        $id = $_GET['id']; //接收传参
        $recharge = Recharge::find() //查找充值数组
            ->where(['in', 'id', $id])
            ->asArray()
            ->all();

        $order_id = Order::getOrder(); //生成订单编号
        foreach ( $recharge as $r){
            $model = new Products(); //实例化模型

            $model->order_id = $order_id;
            $model->product_id = $r['id'];
            $model->product_quantity = '1'; // 商品数量默认为1
            $model->product_name = $r['name'];
            $model->sale = '0';
            $model->product_price = $r['price'];

            $result = $model->save();
        }

        if($result){ //如果添加成功则转跳到产品页面
            return $this->redirect(['/products/index','order' => $order_id, 'realestate' => $realestate]);
        }
    }

    /**
     * Creates a new Recharge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Recharge();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Recharge model.
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
     * Deletes an existing Recharge model.
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
     * Finds the Recharge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recharge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recharge::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
