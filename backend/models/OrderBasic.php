<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_basic".
 *
 * @property integer $id
 * @property string $account_id
 * @property string $order_id
 * @property integer $order_parent
 * @property integer $create_time
 * @property integer $order_type
 * @property integer $payment_time
 * @property string $payment_gateway
 * @property string $payment_number
 * @property string $description
 * @property string $order_amount
 * @property integer $invoice_id
 * @property integer $status
 *
 * @property OrderProducts $order
 * @property OrderRelationshipAddress $order0
 * @property UserAccount $account
 * @property UserInvoice $order1
 */
class OrderBasic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_basic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'order_id', 'create_time', 'order_type', 'order_amount'], 'required'],
            [['order_parent', 'create_time', 'order_type', 'payment_time', 'invoice_id', 'status'], 'integer'],
            [['order_amount'], 'number'],
            [['account_id', 'payment_gateway', 'payment_number'], 'string', 'max' => 64],
            [['order_id'], 'string', 'max' => 15],
            [['description'], 'string', 'max' => 128],
            [['order_id'], 'unique'],
			[['fromdate','todate'],'datetime',],
			[['fromdate','todate'],'default','value'=>'null'],
            /*[['order_id'], 'exist','message'  => '更新无效，请联系管理员', 'skipOnError' => true, 'targetClass' => OrderProducts::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            //[['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderRelationshipAddress::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            //[['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserInvoice::className(), 'targetAttribute' => ['order_id' => 'order_id']],*/
        ];
    }
    
	public $fromdate;
	public $todate;
	// public $add;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'account_id' => '用户',
            'order_id' => '订单编号',
            'order_parent' => 'Order Parent',
            'create_time' => '创建时间',
            'order_type' => '类型',
            'payment_time' => '付款时间',
            'payment_gateway' => '支付方',
            'payment_number' => '交易编号',
            'description' => '详情',
            'order_amount' => '合计',
            'invoice_id' => 'Invoice ID',
            'status' => '状态',
			'fromdate' => 'From','todate' => 'To',
			'add' => '地址',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCom()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'account_id']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Status::className(), ['order_basic_status' => 'status']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderProducts::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder0()
    {
        return $this->hasOne(OrderRelationshipAddress::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder1()
    {
        return $this->hasOne(UserInvoice::className(), ['order_id' => 'order_id']);
    }
	
	//首页显示订单
	public static function getOr()
	{
		$order = (new \yii\db\Query())->select([
			'order_basic.order_id','order_basic.order_amount','order_basic.payment_time',
			'order_relationship_address.address',
		])
			->from('order_basic')
			->join('left join', 'order_relationship_address', 'order_relationship_address.order_id = order_basic.order_id');
		
		return $order;
	}
}
