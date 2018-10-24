<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_accumulate".
 *
 * @property int $id 序号
 * @property string $account_id 用户ID
 * @property double $amount 用户积分
 * @property int $type 积分类型，1=>物业，2=>商城
 * @property string $property 备注
 *
 * @property UserAccount $account
 * @property UserData $account0
 */
class StoreAccumulate extends \yii\db\ActiveRecord
{
    public $name;
    public $phone;
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
            [['account_id', 'amount', 'type'], 'required'],
            [['amount'], 'number'],
            [['type', 'update_time'], 'integer'],
            [['account_id', 'property'], 'string', 'max' => 50],
            [['account_id', 'type'], 'unique', 'targetAttribute' => ['account_id', 'type']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => '用户ID',
            'amount' => '总数',
            'type' => '类型',
            'update_time' => '更新时间',
            'property' => '备注',
        ];
    }

    //自动转换时间
    function afterFind()
    {
        parent::afterDelete();
        $this->update_time = date('Y-m-d H:i:s', $this->update_time);
    }

    function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->update_time = time(); //不论是添加还是更新都查询最新时间

        return true;
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
    public function getData()
    {
        return $this->hasOne(UserData::className(), ['account_id' => 'account_id']);
    }
}
