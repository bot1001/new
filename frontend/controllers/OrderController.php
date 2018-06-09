<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use common\models\Products;
use common\models\OrderAddress as Address;
use frontend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
		$data = (new \yii\db\Query)
			->select('order_basic.id as id,order_basic.order_id as order_id, order_basic.create_time as create_time,
			order_basic.order_type as type, order_basic.payment_time as payment_time,
			order_basic.payment_gateway as gateway, order_basic.description as description,
			order_basic.order_amount as amount, order_basic.status as status,
			order_relationship_address.address as address, user_data.real_name as name')
			->from('order_basic')
			->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
			->join('inner join', 'user_data', 'user_data.account_id = order_basic.account_id')
			->all();
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'data' => $data,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = (new \yii\db\query())
			->select('order_basic.*, order_relationship_address.address as address, user_data.real_name as name')
			->from('order_basic')
			->join('inner join', 'user_data', 'user_data.account_id = order_basic.account_id')
			->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
			->where(['order_basic.id' => "$id"])
			->one();
		
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($order_id, $amount)
    {
		$order = Products::find()
			->select('product_id as p_id, sale')
			->where(['in', 'order_id', $order_id])
			->where(['in', 'sale', '0'])
			->asArray()
			->all();		
		
		$user = $_SESSION['user']; //用户信息
		$house = $_SESSION['home']; //用户下单房屋信息
		
		$account_id = $user['account_id'];
		$type = '1'; //物业订单
		$description = '物业缴费';
		
		$name = $user['user_name']; //下单人
		$phone = $user['mobile_phone']; //手机号码
		$address = $house['community'].' '.$house['building'].' '.$house['number'].'单元'.' '.$house['room'].'号'; //订单地址
		$province = $user['province_id'];
		$city = $user['city_id'];
		$area = $user['area_id'];
		
        $model = new Order(); //实例化订单模型
		
		$transaction = Yii::$app->db->beginTransaction(); //标记事务
		try{
		    $model->account_id = $account_id;
		    $model->order_id = $order_id;
		    $model->create_time = time();
		    $model->order_type = $type;
		    $model->description = $description;
		    $model->order_amount = $amount;
		    
		    $e = $model->save(); //保存
			$o_id = Yii::$app->db->getLastInsertID(); //获取最新插入的订单ID
			
			if($e){
				$add = new Address(); //实例化订单地址模型
				
				$add->order_id = $order_id;
				$add->address = $address;
				$add->mobile_phone = $phone;
				$add->name = $name;
				$add->province_id = $province;
				$add->city_id = $city;
				$add->area_id = $area;
				
				$a = $add->save(); //保存
			}
			if($a){
				$transaction->commit(); //提交事务
			}else{
				$transaction->rollback(); //滚回事务
			}
		}catch(\Exception $e) {
		    $transaction->rollback(); //滚回事务
        }

        if (isset($a)) {
            return $this->redirect(['view', 'id' => $o_id]);
        }else{
			return $this->redirect(Yii::$app->request->referrer);
		}
    }

    /**
     * Updates an existing Order model.
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
	
	//拉起支付
	public function actionPay($id)
	{
		$order = Order::find()
			->where(['in', 'id', $id])
			->asArray()
			->one();
		
		return $this->renderAjax('pay', ['order' => $order]);
	}
	
	//打印订单
	public function actionPrint()
	{
		echo 'test';
	}

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		$order = Order::find()->where(['id' => "$id"])->asArray()->one();
		$order_id = $order['order_id'];
		
		$transaction = Yii::$app->db->beginTransaction(); //标记事务
		try{
			$or = Order::findOne(['order_id' => "$order_id"])->delete();
			if($or){
				$address = Address::findOne(['order_id' => "$order_id"])->delete();
			}
			
			if($address && $or){
				//查找要删除的数据
				$pro = Products::find()
					->select('id')
					->where(['order_id' => "$order_id"])
					->asArray()
					->all();
				
				//删除数据
				foreach($pro as $p){
					$products = Products::findOne($p['id'])->delete();
				}
			}
			
			if($or && $products && $address)
			{
				$transaction->commit();
			}else{
				$transaction->rollback();
			}
		}catch(\Exception $e){
			print_r($e);exit;
			$transaction->rollback();
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
