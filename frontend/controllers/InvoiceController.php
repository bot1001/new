<?php

namespace frontend\controllers;

use Yii;
use common\models\Invoice;
use common\models\Products as Order;
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
		
		if(isset($_GET['order_id'])){
			$order_id = $_GET['order_id'];
			$searchModel->order_id = $order_id;
		}
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionWx($id)
	{
		$data = [ '0' => '欠费', '1' => '支付宝', '2' => '微信', '3' => '刷卡', '4' => '银行', '5' => '政府', '6' => '现金', '7' => '建行', '8' => '优惠' ];
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
    public function actionView()
    {
		$id = $_SESSION['home']['id'];
		$invoice = Invoice::find()
			->select('invoice_id, year, month, description, invoice_amount as amount, invoice_notes as notes')
			->where([ 'invoice_status'=> '0', 'realestate_id'=> "$id"])
			->asArray()
			->all();
		
		return $this->render('view', ['invoice' => $invoice]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
		
		$id = $_SESSION['home']['id']; //提取关联房屋id
		
		$cost = (new \yii\db\Query()) //查找房屋绑定的固定费用
			->select('cost_name.cost_name as cost, cost_name.price as price,
			          cost_name.sale as sale,
			          cost_name.property as property, cost_name.formula as formula')
			->from('cost_name')
			->join('inner join', 'cost_relation', 'cost_relation.cost_id = cost_name.cost_id')
			->andwhere(['cost_relation.realestate_id' => $id, 'cost_relation.status' => '1'])
			->andwhere(['in', 'cost_name.inv', '1'])
			->andwhere(['<', 'cost_relation.from', time()])
			->all();

		if(empty($cost))
        {
            echo "<script>alert('您的房屋无绑定费项目，请联系物业服务中心')</script> ";
            exit;
        }

        if ($model->load(Yii::$app->request->post())) 
		{
			$post = $_POST['Invoice']; //接收预交数据
			$month = $post['month']; //获取预交月数
			$year = $post['year']; //获取预交起始月份
			$id = $_SESSION['home']['id']; //提取关联房屋id
			//计算预交费项
			
			$prepay = Invoice::prepay($cost, $year, $month, $id);
			
			if($prepay == ''){
		     	return $this->redirect(Yii::$app->request->referrer);
		     }     
			
            return $this->render('prepay', ['prepay' => $prepay, 'cost' => $cost, 'year' => $year, 'month' => $month, 'id' => $id]);
        }
		
		return $this->renderAjax('create', [
            'model' => $model,
			'cost' => $cost,
        ]);
    }
	
	//保存用户预交数据
	public function actionNew($year, $month, $amount)
	{
		$get = $_GET; //接收传过来的数据
		
		$id = $get['id']; // 接收房号
		$cost = $get['cost']; //接收预交费项
		
		$prepay = Invoice::prepay($cost, $year, $month, $id); //组合缴费项目
		
		if($prepay == ''){
			echo '费项重复';exit;
		}
		
		$order_id = \common\models\Order::getOrder(); //获取订单编号
		
		$des = '物业缴费'; //订单描述
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
			$m = 1;
			$o = 1;
			foreach($prepay as $key => $pre)
		    {
			    $model = new Invoice(); //实例化费项模型
				$m ++;
				
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
					$o ++;
			    	$order = new Order(); //实例化订单产品ID
			    	$order->order_id = $order_id;
			    	$order->product_id = $p_id;
			    	$order->product_quantity = '1';
			    	$order->sale = $pre['sale'];
			    	
			    	$order->save(); //保存
			    }
			}
			if($m == $o){
				$transaction->commit();
			}else{
				$transaction->rollback();
			}
		}catch(\Exception $e) {
			print($e);
		    $transaction->rollback();exit; //滚回事务
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
    public function actionPay($amount)
    {
        $id = $_SESSION['home']['id']; //获取当前房号
		
		$invoice_id = Invoice::find() //查询所有未交费费项
			->select('invoice_id, year, month, description, invoice_amount')
			->andwhere(['in', 'invoice_status', '0'])
			->andwhere(['in', 'realestate_id', $id])
			->asArray()
			->all();
		
		$order_id = \common\models\Order::getOrder(); //获取订单编号
		
		$i = 0; //循环次数
		$sale = 0; //优惠标记
		$count = count($invoice_id); //总条数
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
			foreach($invoice_id as $invoice)
		    {
				$i ++;
		    	if($invoice['description'] == '物业费')
				{
		    		if($invoice['year'] > date('Y'))
		    		{
		    			$sale ++; //标记预交物业费信息
		    		}elseif($invoice['year'] == date('Y')){
						if($invoice['month'] > date('m'))
		    			{
		    				$sale ++; //标记预交物业费信息
		    			}
					}
		    	}
		    	
		    	$order = new Order(); //实例化订单产品ID
		    	
		        $order->order_id = $order_id;
		        $order->product_id = $invoice['invoice_id'];
		        $order->product_quantity = '1';
		    	
		    	if($sale%13 == 0){ //判断是否满足优惠条件
		    		$order->sale = '1';
		    	}else{
					$order->sale = '0';
				}
		    	
		        $E = $order->save(); //保存数据
		    }
			
			if($i === $count){
				$transaction->commit(); //提交事务
			}else{
				$transaction->rollback(); //事务回滚
			}
		}catch(\Exception $e){
			print_r($e);
			$transaction->rollback();exit; //事务回滚
		}
		
		return $this->redirect(['/order/create', 'order_id' => $order_id, 'amount' => $amount]);
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
