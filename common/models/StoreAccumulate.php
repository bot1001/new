<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_accumulate".
 *
 * @property int $id 序号
 * @property string $account_id 用户ID
 * @property int $amount 积分总数
 * @property int $income 进出类型，1=>收入， 2=>支出
 * @property string $order_id 订单ID
 * @property int $type 积分类型，1=>物业,2 =>商城
 * @property int $create_time 创建时间
 * @property int $status 积分状态，0=>取消，1=>已确认，2=>待确认，3=>过期
 * @property string $property 备注
 *
 * @property OrderBasic $order
 * @property UserAccount $account
 */
class StoreAccumulate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_accumulate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'amount', 'income', 'order_id', 'type', 'status'], 'required'],
            [['amount', 'income', 'type', 'create_time', 'status'], 'integer'],
            [['account_id', 'order_id', 'property'], 'string', 'max' => 50],
            [['account_id', 'order_id'], 'unique', 'targetAttribute' => ['account_id', 'order_id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
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
            'amount' => '总数',
            'income' => '收支',
            'order_id' => '订单ID',
            'type' => '类型',
            'create_time' => '时间',
            'status' => '状态',
            'property' => '备注',
        ];
    }

    //自动转换时间
    function afterFind()
    {
        parent::afterDelete();
        $this->create_time = date('Y-m-d H:i:s', $this->update_time);
    }

    function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->create_time = time(); //不论是添加还是更新都查询最新时间

        return true;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }

    public function getAddress()
    {
        return $this->hasOne(OrderAddress::className(), ['order_id' => 'order_id']);
    }
}
