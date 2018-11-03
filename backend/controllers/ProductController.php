<?php

namespace backend\controllers;

use common\models\Up;
use common\models\ProductProperty;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                    "imagePathFormat" => "/img/market/{yyyy}{mm}{dd}/{time}{rand:6}", //商城图片
                    "imageMaxSize" => 1024000,
                ],
            ],
            'product' => [ //GridView直接编辑一
                'class' => EditableColumnAction::className(),
                'modelClass' => Product::className(),
                'outputValue' => function ($model, $attribute, $key, $index) {

                },
                'ajaxOnly' => true
            ],
            'property' => [ //GridView直接编辑二
                'class' => EditableColumnAction::className(),
                'modelClass' => ProductProperty::className(),
                'outputValue' => function ($model, $attribute, $key, $index) {
                },
                'ajaxOnly' => true
            ]
        ]);
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $host = Yii::$app->request->hostInfo; //请求网址
        $model = (new Query()) //查找数据
            ->select(["product_basic.product_name as name, product_basic.product_subhead as header,
            store_taxonomy.name as brand, product_basic.product_taxonomy as taxonomy, concat(product_basic.market_price, ' 元') as price,
            concat('$host', product_basic.product_image) as image, product_basic.product_introduction as introduction,
            concat(product_basic.product_sale, ' 元') as sale, concat(product_basic.product_accumulate, ' 元') as accumulate,
            product_basic.product_status as status,store_basic.store_name,
            from_unixtime(product_basic.create_time) as create_time, from_unixtime( product_basic.update_time) as update_time,
            product_basic.product_id as id
            "])
            ->from('product_basic')
            ->join('inner join', 'store_basic', 'store_basic.store_id = product_basic.store_id')
            ->join('inner join', 'store_taxonomy', 'store_taxonomy.id = product_basic.brand_id')
            ->where(['product_id' => "$id"])
            ->one();

        $taxonomy = (new Query()) //查找产品子系列
            ->select('name')
            ->from('store_taxonomy')
            ->where(['id' => $model['taxonomy']])
            ->one();

        $property = ProductProperty::find()
            ->where(['product_id' => $model['id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $property,
            'sort' => false, //取消排序
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        $status = Yii::$app->params['product']['status'];//获取商品状态
        $model['taxonomy'] = $taxonomy['name'];   //重新赋值产品信息

        return $this->render('view', [
            'model' => $model,
            'status' => $status,
            'data' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    //添加商品属性
    function actionAdd($id, $price, $size, $color, $quantity, $image)
    {
        $model = new ProductProperty();//实例化模型

        $model->product_id = $id;
        $model->price = $price;
        $model->size = $size;
        $model->color = $color;
        $model->quantity = $quantity;
        $model->image = $image;

        $reault = $model->save();//保存数据

        if($reault){ //如果保存成功这返回真
            return true;
        }

        return false; //默认返回false
    }

    //删除商品属性
    function actionTrash()
    {
        $ids = $_GET['ids'];
        $i = 0; //设置计数
        foreach ($ids as $id){
            $model = ProductProperty::findOne($id);
            $result = $model->delete();
            if($result){
                $i ++;
            }
        }

        return $i;
    }

    //下架商品
    function actionDown($id, $status)
    {
        $result = Product::updateAll(['product_status' => $status], 'product_id = :p_id', [':p_id' => "$id"]);

        if($result){
            return true;
        }

        return false;
    }

    //修改产品缩略图
    function actionImg($id, $type, $image)
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
                $date = date('YmdH');
                $dir = './img/market/'.$date; //图片保存路径
                if ( !is_dir($dir) ) { //如果文件夹不存在，则创建此文件夹
                    mkdir($dir);
                }
                rename("uplaod/$name", "img/market/$date/$n"); //修改文件名称
            }

            $name =  "/img/market/$date/$n"; //新文件名
            if($type == 'property'){//判断更新类型
                $result = ProductProperty::updateAll(['image' => $name], 'id = :id', ['id' => "$id"]);
            }elseif($type == 'product'){
                $result = Product::updateAll(['product_image' => $name], 'product_id = :id', [':id' => "$id"]);
            }

            if($result){
                return $this->redirect(Yii::$app->request->referrer);
            }

            return false;
        }

        return $this->renderAjax('image',['model' => $model , 'id' => $id, 'image' => $image]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
