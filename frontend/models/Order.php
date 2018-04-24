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
            [['order_parent', 'create_time', 'order_type', 'invoice_id', 'status'], 'integer'],
            [['order_amount'], 'number'],
            [['account_id', 'payment_gateway', 'payment_number'], 'string', 'max' => 64],
            [['order_id'], 'string', 'max' => 15],
            [['description'], 'string', 'max' => 128],
            [['order_id'], 'unique'],

        ];
    }
    
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
			'add' => '地址',
        ];
    }
}
