<?php

namespace backend\controllers;

use common\models\AddressSearch;
use common\models\Area;
use common\models\OrderAddress;
use common\models\Products;
use kartik\grid\EditableColumnAction;
use Yii;
use common\models\Store;
use common\models\StoreSearch;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends Controller
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

    function actions()
    {
        return ArrayHelper::merge(parent::actions(),[
            'store' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => Store::className(),
                'outputValue' => function ($model, $attribute, $key, $index) {

                },
                'ajaxOnly' => true
            ]
        ]);
    }

    /**
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $taxonomy = \common\models\StoreTaxonomy::Taxonomy($type = '1');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taxonomy' => $taxonomy
        ]);
    }

    //订单列表
    function actionOrder()
    {
        $search = new AddressSearch(); //实例化搜索模型
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('order',[
            'dataProvider' => $dataProvider,
            'search' => $search
        ]);
    }

    /**
     * Displays a single Store model.
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

    //订单详情预览
    function actionStoreView($id)
    {
        $order = OrderAddress::find() //查询订单信息
            ->joinWith('order')
            ->where(['order_basic.order_type' => '2', 'order_relationship_address.id' => "$id"])
            ->asArray()
            ->one();

        $province = Area::getOne($order['province_id']);
        $city = Area::getOne($order['city_id']);
        $area = Area::getOne($order['area_id']);

        $address = $province.$city.$area.$order['address']; //拼接地址
        $phone = $order['mobile_phone'];
        $name = $order['name'];
        $order_id = $order['order_id']; //订单编号
        $order_info = $order['order']; //订单信息

        $url = 'http://'.$_SERVER['HTTP_HOST'];
        $product = (new Query())
            ->select(["order_products.product_quantity as count, order_products.product_price as price,
            product_basic.product_name as name, product_basic.product_subhead as header, concat('$url',product_basic.product_image) as image"])
            ->from('order_products')
            ->join('inner join', 'product_basic', 'product_basic.product_id = order_products.product_id')
            ->where(['order_products.order_id' => "$order_id"])
            ->all();

        return $this->render('store-view',[
            'address' => $address,
            'phone' => $phone,
            'name' => $name,
            'order_id' => $order_id,
            'order_info' => $order_info,
            'product' => $product
        ]);
    }

    //商户注册
    function actionRegister()
    {
        $this->layout = 'reg';

        return $this->render('register');
    }

    /**
     * Creates a new Store model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Store();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->store_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->store_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Store model.
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
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
