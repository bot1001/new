<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_del".
 *
 * @property int $invoice_id （账单ID）
 * @property int $realestate_id （关联房屋ID）
 * @property string $description （账单说明）
 * @property string $year  年
 * @property string $month  月
 * @property string $invoice_amount （账单金额）
 * @property int $user_id （操作人）
 * @property string $order_id （订单号，默认为空，支付成功后写入）
 * @property string $invoice_notes （备注）
 * @property string $payment_time （支付时间）
 * @property int $invoice_status （账单状态）（-3,删除,0,未缴纳，1,银行代缴,2,线上已缴纳，3,线下已缴纳）
 * @property string $update_time
 * @property string $property （备注）
 *
 * @property CommunityRealestate $realestate
 * @property OrderBasic $order
 * @property SysUser $user
 */
class InvoiceDel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_del';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['realestate_id', 'description', 'invoice_amount', 'user_id', 'invoice_status', 'property'], 'required'],
            [['realestate_id', 'user_id', 'invoice_status'], 'integer'],
            [['invoice_amount'], 'number'],
            [['invoice_notes', 'update_time'], 'string'],
            [['description'], 'string', 'max' => 200],
            [['year', 'month'], 'string', 'max' => 4],
            [['order_id', 'property'], 'string', 'max' => 50],
            [['payment_time'], 'string', 'max' => 22],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => 'Invoice ID',
            'realestate_id' => 'Realestate ID',
            'description' => '详情',
            'year' => '年份',
            'month' => '月份',
            'invoice_amount' => '金额',
            'user_id' => 'User ID',
            'order_id' => '订单编号',
            'invoice_notes' => '备注',
            'payment_time' => '支付时间',
            'invoice_status' => '状态',
            'update_time' => '删除时间',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealestate()
    {
        return $this->hasOne(Realestate::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderBasic::className(), ['order_id' => 'order_id']);
    }

    public function getCommunity()
    {
        return $this->hasMany(Community::className(), ['community_id' => 'community_id'])->viaTable('community_realestate', ['realestate_id' => 'realestate_id']);
    }

    function getBuilding()
    {
        return $this->hasOne(Building::className(), [ 'building_id' =>'building_id'])->viaTable('community_realestate', [ 'realestate_id' =>'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
