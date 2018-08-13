<?php

namespace backend\controllers;

use common\models\Invoice;
use common\models\Order;
use common\models\Products;
use Yii;
use app\models\OrderBasic;
use app\models\UserInvoice;
use app\models\CommunityBasic;
use app\models\OrderRelationshipAddress;
use app\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;

/**
 * OrderController implements the CRUD actions for OrderBasic model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
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
	
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
    }

    /**
     * Lists all OrderBasic models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new OrderSearch();
		
		if(isset($_GET['order_id'])){
			$searchModel->order_id = $_GET['order_id'];
		};
		
		//判断来自统计功能模块的参数
		if(isset($_GET['community'])){
			$searchModel->account_id = $_GET['community'];
		};
		
		//判断是否存在时间 one为起始时间，two为截止时间
		if(isset($_GET['one']) && isset($_GET['two']))
		{ 
			$one = date('Y-m-d', time($_GET['one']));
			$two = date('Y-m-d', strtotime("+1day", $_GET['two']));
			$searchModel->payment_time = $one.' to '.$two;
		}
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //作废订单
    function actionTrash($id)
    {
        $status = ['3', '4', '5', '6', '8'];
        $model = Order::find()
            ->select('status')
            ->andwhere(['order_id' => "$id"])
            ->andWhere(['in', 'payment_gateway', $status])
            ->andWhere(['status' => '2'])
            ->column();

        if(!$model){
            return false;
        }

        $invoice = Products::find() //查找费项ID
            ->select('product_id as id')
            ->where(['order_id' => "$id"])
            ->asArray()
            ->all();

        $transaction = Yii::$app->db->beginTransaction(); //开始数据事务
        try{
            foreach($invoice as $in){ //循环数组并更新相关信息
                Invoice::updateAll(['order_id' => '', 'payment_time' => '', 'invoice_status' => '0'], 'invoice_id = :id', [':id' => $in['id']]);
            }
            $order = Order::updateAll(['status' => '100', 'invoice_id' => $_SESSION['user']['0']['id'], 'payment_time' => time()], 'order_id = :o_id', [':o_id' => $id]);
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
        }

        if($order){
            return true;
        }
        return false;
    }
	
	//来自缴费页面订单查询
	public function actionIndex1($order_id)
    {
        $searchModel = new OrderSearch();
		$searchModel -> order_id = $order_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'order' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => OrderBasic::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }
	
	//打印订单
	public function actionPrint($order_id, $amount)
	{
		$session = Yii::$app->session;
		$user_name = $_SESSION['user']['0']['name']; //收款用户名
		
		//获取订单信息
		$order = OrderBasic::find()
			->select('order_id,payment_time,payment_gateway,payment_time')
			->where(['order_id' => $order_id])
			->asArray()
			->one();
		 
		if($order['payment_time'] || $order['payment_gateway']){
			//查询缴费费项信息
			$i = UserInvoice::find()
				->select('community_id,building_id,realestate_id,year,month,description,invoice_amount, invoice_notes as note')
				->where(['order_id'=>$order_id])
				->orderBy('year,month ASC');
						
			foreach ($i->asArray()->batch(200) as $invoice);//单次获取200条费项
			if(empty($invoice)){
				$session->setFlash('m','1');
				return $this->redirect(Yii::$app->request->referrer);
			}
			
		    $de = array_column($invoice, 'description');// 选择费详情列
			$dc = array_unique($de); //去重复
									
			if($invoice){
				$inv = reset($invoice);
				
				//缴费信息
				$comm = (new \yii\db\Query())
					->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as name, community_realestate.owners_name as n, community_realestate.realestate_id as id')
					->from('community_realestate')
					->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
					->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
					->where(['community_realestate.realestate_id' => $inv['realestate_id']])
					->one();
				
				$e = [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行', 8=> '优惠' ];
				return $this->render('print',[
			                      'dc' => $dc,
			                      'comm' => $comm,
					              'order_id' => $order_id,
			                      'amount'=> $amount,
					              'e' => $e,
					              'order' => $order,
			                      'user_name' => $user_name,
					              'invoice' => $invoice,
				                ]);
			}else{
				$session->setFlash('m','1');
				return $this->redirect(Yii::$app->request->referrer);
			}		 
		}else{
			$session->setFlash('m','2');
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	
    /**
     * Displays a single OrderBasic model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = OrderBasic::find()->where(['id' => $id])->asArray()->one();
		$a_id = $model['account_id'];
		$o_id = $model['order_id'];
		$len = strlen($a_id);
		
		if($len < 10){
			$ad = CommunityBasic::find()
				->select('community_name as ad')
			    ->where(['community_id' => $a_id])
				->asArray()
			    ->one();
		}else{		
		    $ad = OrderRelationshipAddress::find()
		    	->select('address as ad')
		    	->where(['order_id' => $o_id])
		    	->asArray()
		    	->one();
		}
		
		$model = array_merge($ad,$model);
		
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }
	
	//支付宝缴费成功后转到这里
	public function actionV($out_trade_no)
	{
		$model = OrderBasic::find()
			->where(['order_id' => $out_trade_no])
			->asArray()
			->one();
		
		return $this->render('view', [
            'model' =>$model,
        ]);
	}
	
    /**
     * Creates a new OrderBasic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderBasic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	
	//确认缴费详情
	public function actionAffirm()
    {
		$id = Yii::$app->request->get();
		foreach($id as $k => $s)
		{
			$ids = ($s['ids']);
			
			if(empty($s)){
				$session = Yii::$app->session;
				$session->setFlash('fail','4');
			       return $this->redirect( Yii::$app->request->referrer );
			}
			
			//获取缴费项目
		    $invoice = (new \yii\db\Query())
				->select('user_invoice.community_id as c_id,
				       community_basic.community_name as community,
			           community_building.building_name as building,
					   community_realestate.room_number as number,
					   community_realestate.room_name as name,
			           user_invoice.invoice_id as id,
			           user_invoice.year as year,
			           user_invoice.month as month,
			           user_invoice.invoice_amount as amount,
			           user_invoice.description as description')
				->from('user_invoice')
				->join('inner join', 'community_basic', 'community_basic.community_id = user_invoice.community_id')
				->join('inner join', 'community_building', 'community_building.building_id = user_invoice.building_id')
				->join('inner join', 'community_realestate', 'community_realestate.realestate_id = user_invoice.realestate_id')
		       	->andwhere(['in', 'user_invoice.invoice_id', $ids])
		       	->andwhere(['user_invoice.invoice_status' => '0'])
		    	->limit(300)
		       	->all();
	    }
		
		if(empty($invoice)){
			$session = Yii::$app->session;
			$session->setFlash('fail','1');
			return $this->redirect( Yii::$app->request->referrer );
		}
		$in = array_column($invoice, 'amount'); //提取金额
		$id = array_column($invoice, 'id'); //提取费项ID
		$m = array_sum($in); //求和金额
		$n = count($in); //合计金额
		
		$a = reset($invoice);
		
		$c_id = $a['c_id']; //小区编号
		$c = $a['community']; //小区
		$b = $a['building']; //楼宇
		$number = $a['number']; //单元
		$name = $a['name']; //房号
		
		$address = $c.'-'.$b.'-'.$name; //拼接地址
		
		return $this->render('add', [
			    'invoice' => $invoice,
			    'n' => $n,
			    'm' => $m,
			    'id' => $id,
			    'c_id' => $c_id,
			    'address' => $address
            ]);
    }

	public function actionAdd($c,$address, $c_id)
	{		
		$id = $_GET['id'];
		
		$order_id = OrderBasic::Order($c, $id, $c_id, $address); //生成订单信息
			
        $model = OrderBasic::find()
			->where(['order_id' => "$order_id"])
			->asArray()
			->one();
		
		$ad = CommunityBasic::find()
				->select('community_name as ad,community_id')
			    ->where(['in', 'community_id', $model['account_id']])
				->asArray()
			    ->one();

		$model = array_merge($model,$ad);

		return $this->renderAjax('view', [
            'model' => $model,
        ]);
	}
		
    /**
     * Updates an existing OrderBasic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OrderBasic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrderBasic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderBasic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderBasic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
