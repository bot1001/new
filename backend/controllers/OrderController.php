<?php

namespace backend\controllers;

use Yii;
use app\models\OrderBasic;
use app\models\UserInvoice;
use app\models\CommunityBasic;
use app\models\CommunityBuilding;
use app\models\CommunityRealestate;
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
	public function actionPrint($order_id)
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
			
		    $in = array_column($invoice, 'invoice_amount');// 选择费项金额列
		    $de = array_column($invoice, 'description');// 选择费详情列
		    $i_a = array_sum($in);  //费项总和
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
				
				$e = [ 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行' ];
				return $this->render('print',[
			                      'dc' => $dc,
			                      'comm' => $comm,
					              'order_id' => $order_id,
			                      'i_a'=> $i_a,
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
	
	//建行支付成功后转跳到此地址
	public function actionJianh()
	{
		return $this->render('jianh');
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
	
	//生成订单后转跳到这
	public function actionView1($order_id)
    {
		$model = OrderBasic::find()
			//->select('id,account_id')
			->where(['order_id' => $order_id])
			->asArray()
			->one();
		
		$c_id = $model['account_id'];
		$ad = CommunityBasic::find()
				->select('community_name as ad,community_id')
			    ->where(['community_id' => $c_id])
				->asArray()
			    ->one();
//		print_r($c_id);
//		echo '<br />';
//		print_r($ad);
//		
//		exit;
		$model = array_merge($model,$ad);

		return $this->render('view', [
            'model' => $model,
        ]);
	}
	
	//接收微信支付二维码
	public function actionWx($url)
	{
		//生成支付二维码
		$img = '<img src=http://paysdk.weixin.qq.com/example/qrcode.php?data='.urlencode($url).' style="width:300px;height:300px;"/>';
		
		return $this->render('wx',[
			'img' => $img
		]);
	}
	
	//接收建行二维码
	public function actionJh()
	{
		$f = $_GET['f'];
		$order_id = $_GET['order_id'];
		$order_amount = $_GET['order_amount'];
		
		return $this->render('jh',[
			'f' => $f,
			'order_amount' => $order_amount,
			'order_id' => $order_id
		]);
	}

	//支付宝缴费成功后转到这里
	public function actionV($out_trade_no)
	{
		$model = OrderBasic::find()
			//->select('id,account_id')
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
		    	->limit(100)
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
								
		//随机产生12位数订单号，格式为年+月+日+1到999999随机获取6位数
		$order_id = date('ymd').str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		$time = date(time());//生成时间
		$des = '物业相关费用'; //订单描述
		$phone = $_SESSION['user']['0']['phone']; //用户联系方式
		$name = $_SESSION['user']['0']['name']; //用户姓名
		$user_id = $c_id; //小区编号
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
			//插入订单
			$sql = "insert into order_basic(account_id,order_id,create_time,order_type,description, order_amount)
			values ('$user_id','$order_id','$time','1','$des','$c')";
			$result = Yii::$app->db->createCommand($sql)->execute();
			if($result){
				foreach($id as $d){
					$sql1 = "insert into order_products(order_id,product_id,product_quantity)value('$order_id','$d','1')";
					$result1 = Yii::$app->db->createCommand($sql1)->execute();
				}
				if($result1){
					$sql2 = "insert into order_relationship_address(order_id,address,mobile_phone,name)
					value('$order_id','$address', '$phone','$name')";
					$result2 = Yii::$app->db->createCommand($sql2)->execute();
				}
			}
			$transaction->commit();
		}catch(\Exception $e) {
		    print_r($e);die;
            $transaction->rollback();
            return $this->redirect(Yii::$app->request->referrer);
        }
			
        return $this->redirect(['view1', 'order_id'=>$order_id]); //跳到支付通道选择页面
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
