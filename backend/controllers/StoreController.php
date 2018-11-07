<?php

namespace backend\controllers;

use app\models\SysUser;
use common\models\AddressSearch;
use common\models\Area;
use common\models\Information;
use common\models\OrderAddress;
use common\models\Up;
use common\models\User;
use common\models\StoreAccount;
use kartik\grid\EditableColumnAction;
use mdm\admin\models\Assignment;
use Yii;
use common\models\Store;
use common\models\StoreSearch;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/img/Adertising/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径、广告
                    "imageMaxSize" => 1024000,
                ],
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

        $order = Yii::$app->params['order']; //订单状态
        unset($order['way']['0']);

        return $this->render('order',[
            'dataProvider' => $dataProvider,
            'search' => $search,
            'order' => $order
        ]);
    }

    /**
     * Displays a single Store model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        if (isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
            $id = $_SESSION['community']['0'] ;
        }

        $model = (new Query())
            ->select('store_basic.store_id as id, store_basic.store_name as name, store_basic.store_phone as phone,
            store_basic.store_cover as cover, store_basic.province_id as province,
            store_basic.city_id as city, area.area_name as area, store_basic.person,
            store_basic.store_address as address, store_basic.store_introduce as introduce,
            store_basic.store_code as code, store_basic.store_people as people, 
            from_unixtime(store_basic.add_time) as time,
            store_basic.is_certificate as certificate, store_basic.store_sort as sort,
            store_basic.store_status as status, store_basic.type, store_taxonomy.name as taxonomy')
            ->from('store_basic')
            ->join('inner join', 'store_taxonomy', 'store_basic.store_taxonomy = store_taxonomy.id')
            ->join('inner join', 'area', 'store_basic.area_id = area.id')
            ->where(['store_basic.store_id' => "$id"])
            ->one();

        $model['province'] = Area::getOne($model['province']);
        $model['city'] = Area::getOne($model['city']);

        return $this->render('view', [
            'model' => $model,
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

    //商户注册之注册页面
    function actionRegister()
    {
        $this->layout = 'reg';

        return $this->render('register');
    }

    //裕家人商家注册步骤转跳连接
    function actionPassword()
    {
        $this->layout = false;
        $name = $_POST['name'];

        return $this->render("$name");
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
            $sysUser->comment = '商户用户';

            $user = $sysUser->save();//保存数据
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

            if($user && $s){ //如果成功添加用户账号和商城店铺
                $storeAccount = new StoreAccount();//实例化模型

                $storeAccount->user_id = $user_id;
                $storeAccount->work_number = $user_id;
                $storeAccount->store_id = $store_id;
                $storeAccount->role = '1';
                $storeAccount->status = '1';

                $reasult = $storeAccount->save(); //保存数据

                $role = new Assignment($id = $user_id); //保存用户角色
                $success = $role->assign($items = ['商户']);

                if($reasult && $success){
                    $transaction->commit();
                    $message = '新商户注册，请及时审批';
                    Information::add($store = $store_id, $message, $type = '2', $number = $store_id);

                    return true;
                }
            }
            $transaction->rollback();//数据回滚

        }catch (\Exception $e){
            $transaction -> rollBack();
        }

        return false;
    }

    //商户审核
    function actionCheck($id, $status)
    {
        $result = Store::updateAll(['store_status' => "$status"], 'store_id = :id', [':id' => $id]);
        if($result){
            return true;
        }

        return false;
    }

    //商户关闭店铺
    function actionDown($id, $status)
    {
        $result = Store::updateAll(['store_status' => "$status"], 'store_id = :id', [':id' => $id]);
        if($result){
            return true;
        }

        return false;
    }

    //更新缩略图
    function actionImg($image, $id)
    {
        $model = new Up();
        if(Yii::$app->request->isPost)
        {
            $model->file = UploadedFile::getInstance( $model, 'file' );
            $name = $_FILES[ 'Up' ][ 'name' ][ 'file' ]; //保存文件名
            $g = pathinfo( $name, PATHINFO_EXTENSION );
            $g = strtolower($g); //全部转换成小写
            $_format = ['png', 'jpg', 'jpeg', 'gif'];
            if(!in_array($g, $_format) )
            {
                echo '文件类型错误';
                return false;
            }

            $n = date(time()).rand(0, 9999).".$g";//新文件名
            if ( $model->upload() ) {
                $date = date('Ymd');
                $dir = './img/market/'.$date; //图片保存路径
                if ( !is_dir($dir) ) { //如果文件夹不存在，则创建此文件夹
                    mkdir($dir);
                }
                rename("uplaod/$name", "img/market/$date/$n"); //修改文件名称
            }

            $name =  "/img/market/$date/$n"; //新文件名

            $result = Store::updateAll(['store_cover' => $name], 'store_id = :id', ['id' => "$id"]);

            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->renderAjax('image', ['model' => $model ,'image' => $image, 'id' => $id]);
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
