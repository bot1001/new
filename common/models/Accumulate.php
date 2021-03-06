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
class Accumulate extends \yii\db\ActiveRecord
{
    public $name;
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
            [['account_id', 'amount', 'income', 'order_id', 'type', 'create_time', 'status', 'property'], 'required'],
            [['amount', 'income', 'type', 'create_time', 'status'], 'integer'],
            [['account_id', 'order_id', 'property'], 'string', 'max' => 50],
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
            'id' => '序号',
            'account_id' => '客户ID',
            'amount' => '积分',
            'income' => '支出类型',
            'order_id' => '订单号',
            'type' => '积分类型',
            'create_time' => '创建时间',
            'status' => '状态',
            'property' => '备注',
        ];
    }

    //时间转换
    function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->create_time = date('Y-m-d H:i:s', $this->create_time);
    }

    //功能模块变量集合
    static function arr($one)
    {
        $status = ['0' => '取消', '1' => '已确认', '2' => '待确认', '3' => '过期'];
        $income = ['1' => '收入', '2' => '支出'];
        $type = ['1' => '物业', '2' => '商城'];

        $result = ['status' => $status, 'income' => $income, 'type' => $type];
        return $result[$one];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

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
}
