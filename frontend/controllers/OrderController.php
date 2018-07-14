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
use yii\data\Pagination;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login/login']);
            return false;
        }
        return true;
    }
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
		$account_id = $_SESSION['user']['account_id'];
		$date = (new \yii\db\Query)
			->select('order_basic.id as id,order_basic.order_id as order_id, order_basic.create_time as create_time,
			order_basic.order_type as type, order_basic.payment_time as payment_time,
			order_basic.payment_gateway as gateway, order_basic.description as description,
			order_basic.order_amount as amount, order_basic.status,
			order_relationship_address.address as address, user_data.real_name as name')
			->from('order_basic')
			->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
			->join('inner join', 'user_data', 'user_data.account_id = order_basic.account_id')
			->where(['order_basic.account_id' => "$account_id"])
			->orderBy('order_basic.status ASC, order_basic.order_id DESC');
		
		$count = $date->count();// 计算总数
		
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => '6']);// 创建分页对象
		
		// 使用分页对象来填充 limit 子句并取得文章数据
        $data = $date->offset($pagination->offset)
                   ->limit($pagination->limit)
                   ->all();
		
        return $this->render('index', [
			'pagination' => $pagination,
            'data' => $data,
			'count' => $count
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $community)
    {
		$model = (new \yii\db\query())
			->select('order_basic.*, order_relationship_address.address as address, user_data.real_name as name')
			->from('order_basic')
			->join('inner join', 'user_data', 'user_data.account_id = order_basic.account_id')
			->join('inner join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id')
			->where(['order_basic.id' => "$id"])
			->one();
		
        return $this->render('view', [
            'model' => $model, 'community' => $community
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($order_id, $amount)
    {
        $community = $_SESSION['home']['community_id']; //小区编码
		$o_id = Order::create($order_id, $amount); //调用函数生成订单

        if (isset($o_id)) {
            return $this->redirect(['view', 'id' => $o_id, 'community' => $community]);
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
	public function actionPay($id, $community)
	{
		$order = Order::find()
			->where(['in', 'id', $id])
			->asArray()
			->one();

		return $this->renderAjax('pay', ['order' => $order, 'community' => $community]);
	}
	
	//打印订单
	public function actionPrint($id, $amount)
	{
		$invoice = \common\models\Invoice::find()->where(['order_id' => $id])->orderBy('year DESC, month DESC')->asArray()->all();
		$order = Order::find(['order_id' => $id])->asArray()->one();
		
		echo '<pre>';
		print_r($order);
			exit;
		
		return $this->render('print', ['amount' => $amount, 'order' => $order, 'order_id' => $id]);
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
				return true;
			}else{
				$transaction->rollback();
			}
		}catch(\Exception $e){
			print_r($e);exit;
			$transaction->rollback();
		}
        return false;
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
