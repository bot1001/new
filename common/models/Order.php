<?php

namespace common\models;

use Yii;
use common\models\Products;
use common\models\OrderAddress as Address;

/**
 * This is the model class for table "order_basic".
 *
 * @property int $id
 * @property string $account_id （关联用户ID）
 * @property string $order_id
 * @property int $order_parent （父级订单，默认0）
 * @property int $create_time （生成时间）
 * @property int $order_type （订单类型，1-物业缴费；2-实物订单）
 * @property int $payment_time （付款时间）
 * @property string $payment_gateway （付款通道）1；支付宝，2：微信
 * @property string $payment_number （交易编号）
 * @property string $description （订单说明）
 * @property string $order_amount （订单总额）
 * @property int $invoice_id （账单ID，如果订单类型为1）
 * @property int $status （订单状态，默认1-未支付，2-已支付，3-已取消 4-送货中 5-已签收）
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_basic';
    }
	
	public $name;
	public $address;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'order_id', 'create_time', 'order_type', 'order_amount'], 'required'],
            [['order_parent', 'create_time', 'order_type', 'payment_time', 'invoice_id', 'status'], 'integer'],
            [['order_amount'], 'number'],
            [['account_id', 'payment_gateway', 'payment_number', 'description', 'property'], 'string', 'max' => 64],
            [['order_id'], 'string', 'max' => 15],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'order_id' => '缴费单号',
            'order_parent' => '父级订单',
            'create_time' => '创建时间',
            'order_type' => '订单类型',
            'payment_time' => '支付时间',
            'payment_gateway' => '支付方式',
            'payment_number' => '支付单号',
            'description' => '详情',
            'order_amount' => '合计',
            'invoice_id' => '订单类型', //多余字段
            'status' => '状态',
            'property' => '备注'
        ];
    }
	
	public function getProducts() 
	{ 
	    return $this->hasMany(OrderProducts::className(), ['order_id' => 'order_id']); 
	} 
	
	/** 
	 * @return \yii\db\ActiveQuery 
	 */ 
	public function getAddress() 
	{ 
	    return $this->hasOne(OrderAddress::className(), ['order_id' => 'order_id']); 
	}
	
	/** 
	 * @return \yii\db\ActiveQuery 
	 */ 
	public function getAccount() 
	{ 
	    return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']); 
	}
	
	public function getData() 
	{ 
	    return $this->hasOne(UserData::className(), ['account_id' => 'account_id']); 
	}
	
	//裕家人后台生成订单
	public static function getOrder()
	{
		//随机产生12位数订单号，格式为年+月+日+1到999999随机获取6位数
		$order_id = date('ymd').str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		return $order_id;
	}

	//裕家人小程序生成订单
	public static function getOrder02()
	{
		//随机产生12位数订单号，格式为年+月+日+1时+分+秒
		$order_id = date('ymdHis');
		return $order_id;
	}

	static function create($order_id, $amount)
    {
        $user = $_SESSION['user']; //用户信息
        $house = $_SESSION['home']; //用户下单房屋信息

        $account_id = $user['account_id'];
        $type = '1'; //物业订单
        $description = '物业缴费';
        $community = $house['community_id']; //小区编码

        $name = $user['real_name']; //下单人
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

        if($a && $o_id)
        {
            return $o_id;
        }else{
            return false;
        }
    }
}
