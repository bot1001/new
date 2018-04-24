<?php

namespace frontend\controllers;

use Yii;
use common\models\UserInvoice;
use yii\web\Controller;

class OrderController extends Controller
{
	public function actionIndex($id)
	{
		$invoice = UserInvoice::find()
			->select('invoice_id, invoice_amount')
			->where(['invoice_status' => '0', 'realestate_id' => "$id"])
			->asArray()
			->all();
		$i = array_column($invoice, 'invoice_id'); //提取费项ID
		$invoice_amount = array_column($invoice, 'invoice_amount'); //提取合计金额
		$amount = array_sum($invoice_amount); //总金额求和
				
		//随机产生12位数订单号，格式为年+月+日+1到999999随机获取6位数
		$order_id = date('ymd').str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		$time = date(time());//订单类型
		$des = '物业相关费用'; //订单描述
		$phone = '15296500211';//$_SESSION['user']['phone'];
		$name = 'test';//$_SESSION['user']['name'];
		$user_id = '082bb6465bb095acef7ab33f0e3944e0'; //测试，过后需要
		$address = 'test';  //测试，过后需要
				
		if(!empty($user_id)){
			$transaction = Yii::$app->db->beginTransaction();
			try{
				//插入订单
				$sql = "insert into order_basic(account_id,order_id,create_time,order_type,description, order_amount)
				values ('$user_id','$order_id','$time','1','$des','$amount')";
				$result = Yii::$app->db->createCommand($sql)->execute();
				if($result){
					foreach($i as $d){
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
			
        return $this->redirect(['/order/view', 'order_id'=>$order_id, 'amount' => $amount]); //跳到支付通道选择页面
		}
	}
	
	//订单预览
	public function actionView($order_id, $amount)
	{
		return $this->render('/order/view',['order_id'=>$order_id, 'amount' => $amount]);
	}
}
?>