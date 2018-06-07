<?php

namespace frontend\controllers;

use Yii;
use common\models\Invoice;
use common\models\OrderProducts as Order;
use frontend\models\InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'data' => $data
        ]);
    }
	
	public function actionWx($id)
	{
		$data = [ '0' => '欠费', '1' => '银行', '2' => '线上', '3' => '刷卡', '4' => '优惠', '5' => '政府', '6' => '现金' ];
		$invoice = UserInvoice::find()
			->select('year, month, description, invoice_amount as amount, invoice_status as status')
			->where(['in', 'realestate_id', "$id"])
			->orderBy('invoice_status ASC, year DESC, month DESC')
			->asArray()
			->all();
		
	    return $this->render('index',['invoice' => $invoice, 'data' => $data, 'id' => $id]);
	}

    /**
     * Displays a single Invoice model.
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
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
		
		$id = array_column($_SESSION['house'], 'id'); //提取关联房屋id
		
		$cost = (new \yii\db\Query()) //查找房屋绑定的固定费用
			->select('cost_name.cost_name as cost, cost_name.price as price, 
			cost_name.property as property, cost_name.formula as formula')
			->from('cost_name')
			->join('inner join', 'cost_relation', 'cost_relation.cost_id = cost_name.cost_id')
			->andwhere(['in', 'cost_relation.realestate_id', $id])
			->andwhere(['in', 'cost_name.inv', '1'])
			->all();

        if ($model->load(Yii::$app->request->post())) 
		{
			$post = $_POST['Invoice']; //接收预交数据
			$month = $post['month']; //获取预交月数
			
			//计算预交费项
			$prepay = Invoice::prepay($cost, $month, $id);
			
			
            return $this->render('prepay', ['prepay' => $prepay, 'cost' => $cost, 'month' => $month, 'id' => $id]);
        }
		
		return $this->render('create', [
            'model' => $model,
			'cost' => $cost,
        ]);
    }
	
	//用户预交保存操作
	public function actionNew()
	{
		$get = $_GET; //接收传过来的数据
		
		$id = $get['id']; // 接收房号
		$month = $get['month']; //接收预交费月数
		$cost = $get['cost']; //接收预交费项
		$amount = $get['amount']; //接收需要支付的金额
		
		$prepay = Invoice::prepay($cost, $month, $id); //组合缴费项目
		
		//随机产生12位数订单号，格式为年+月+日+1到999999随机获取6位数
		$order_id = date('ymd').str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		$des = '物业相关费用'; //订单描述
		
		foreach($prepay as $key => $pre)
		{$model = new Invoice(); //实例化费项模型
			$model->community_id = $pre['community_id'];
		    $model->building_id = $pre['building_id'];
		    $model->realestate_id = $pre['id'];
		    $model->year = $pre['year'];
			$model->month = $pre['month'];
			$model->description = $pre['description'];
		    $model->create_time = time();
		    $model->invoice_amount = $pre['amount'];
		    $model->invoice_notes = $pre['notes'];
		    $model->invoice_status = '0';
			
		    $e = $model->save(); //保存
			$p_id = Yii::$app->db->getLastInsertID(); //最新插入的数据ID
			
			if($e){
				$order = new Order(); //实例化订单产品ID
				$order->order_id = $order_id;
				$order->product_id = $p_id;
				$order->product_quantity = '1';
				$order->sale = $pre['sale'];
				
				$order->save(); //保存
			}
		}
		
		return $this->redirect(['/order/create', 'order_id' => $order_id, 'amount' => $amount]);
	}

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->invoice_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
