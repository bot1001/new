<?php

namespace backend\controllers;

use app\models\SysUser;
use common\models\AddressSearch;
use common\models\Area;
use common\models\OrderAddress;
use common\models\User;
use common\models\StoreAccount;
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

    //裕家人商家注册步骤转跳连接
    function actionPassword()
    {
        $this->layout = false;
        $name = $_POST['name'];

        return $this->render("$name");
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

    //裕家人商户注册之保存信息
    function actionR($phone, $Name, $password, $type, $code, $name, $address, $tax, $count, $person, $qr)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $sysUser = new SysUser(); //实例化模型

            //块赋值
            $sysUser->company = '';
            $sysUser->real_name = $Name;
            $sysUser->name = $Name;
            $sysUser->phone = $phone;
            $sysUser->salt = '2';
            $sysUser->status = '1';
            $sysUser->new_pd = md5($password);
            $user = $sysUser->comment = '商户用户';

            $s = $sysUser->save();//保存数据
            $user_id = Yii::$app->db->getLastInsertID();

            $store = new Store(); //实例化商城模型

            $store->store_name = $name;
            $store->store_phone = $phone;
            $store->store_cover = '';
            $store->province_id = '450000';
            $store->city_id = '451300';
            $store->area_id = '451302';
            $store->person = $person;
            $store->store_address = $address;
            $store->store_introduce = '';
            $store->store_code = $code;
            $store->store_people = $count;
            $store->add_time = date('Y-m-d H:i:s');
            $store->is_certificate = '0';
            $store->store_sort = '0';
            $store->store_status = '2';
            $store->type = $type;
            $store->store_taxonomy = $tax;

            $s = $store->save(); //保存数据
            $store_id = Yii::$app->db->getLastInsertId();

            print_r($user_id);
            print_r($store_id);

            if($user && $s){
                $storeAccount = new StoreAccount();//实例化模型

                $storeAccount->user_id = $user_id;
                $storeAccount->work_number = $user_id;
                $storeAccount->store_id = $store_id;
                $storeAccount->role = '1';
                $storeAccount->status = '1';

                $reasult = $storeAccount->save(); //保存数据

                if($reasult){
                    $transaction->commit();
                }
            }
            $transaction->rollback();//数据回滚

        }catch (\Exception $e){
            $transaction -> rollBack();
        }

        return '路由参数设置正常';
    }

    //商户注册查询
    function actionFind($phone)
    {
        $user = User::find() //查询数据中是否存在此号码
            ->where(['phone' => $phone])
            ->asArray()
            ->one();

        if($user){
            return false;
        }else{
            return true;
        }
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
