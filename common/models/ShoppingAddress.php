<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shopping_address".
 *
 * @property int $id ID序号
 * @property string $account_id 用户ID
 * @property string $name 收货人
 * @property string $phone 电话
 * @property string $address 用户地址
 * @property int $update_time 更新时间
 * @property string $property 备注
 *
 * @property UserAccount $account
 */
class ShoppingAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shopping_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'name', 'phone', 'address'], 'required'],
            [['account_id', 'name', 'phone', 'address'], 'unique', 'targetAttribute' => ['account_id', 'name', 'phone', 'address']],
            [['update_time'], 'integer'],
            [['account_id', 'name', 'phone', 'address', 'property'], 'string', 'max' => 50],
            [['account_id', 'name', 'phone', 'address'], 'unique', 'targetAttribute' => ['account_id', 'name', 'phone', 'address']],
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
            'name' => 'Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'update_time' => 'Update Time',
            'property' => 'Property',
        ];
    }

    function beforeSave($insert)
    {
        parent::beforeSave($insert); // 父级
        $this->update_time = time();
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
}
