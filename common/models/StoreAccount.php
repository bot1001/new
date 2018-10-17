<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_account".
 *
 * @property int $id
 * @property int $user_id （账户ID）
 * @property int $work_number （工号）
 * @property int $store_id （商店ID）
 * @property int $role （角色，1=>店长，2=>管理员，3=>职员）
 * @property int $status （状态）
 * @property string $备注 （备注）
 *
 * @property StoreBasic $store
 * @property SysUser $user
 */
class StoreAccount extends \yii\db\ActiveRecord
{
    public $phone;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'work_number', 'store_id', 'role', 'status'], 'required'],
            [['user_id', 'work_number', 'store_id', 'role', 'status'], 'integer'],
            [['property'], 'string', 'max' => 50],
            [['user_id', 'work_number'], 'unique', 'targetAttribute' => ['user_id', 'work_number']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'store_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'work_number' => '工号',
            'store_id' => '关联商城',
            'role' => '职位',
            'status' => '状态',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
