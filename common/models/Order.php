<?php

namespace common\models;

use Yii;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'order_id', 'create_time', 'order_type', 'order_amount'], 'required'],
            [['order_parent', 'create_time', 'order_type', 'payment_time', 'invoice_id', 'status'], 'integer'],
            [['order_amount'], 'number'],
            [['account_id', 'payment_gateway', 'payment_number', 'description'], 'string', 'max' => 64],
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
            'order_id' => 'Order ID',
            'order_parent' => 'Order Parent',
            'create_time' => 'Create Time',
            'order_type' => 'Order Type',
            'payment_time' => 'Payment Time',
            'payment_gateway' => 'Payment Gateway',
            'payment_number' => 'Payment Number',
            'description' => 'Description',
            'order_amount' => 'Order Amount',
            'invoice_id' => 'Invoice ID',
            'status' => 'Status',
        ];
    }
}
