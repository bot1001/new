<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_device".
 *
 * @property integer $id
 * @property string $account_id
 * @property string $device_token
 * @property integer $client
 * @property integer $puduct
 *
 * @property UserRelationshipRealestate $account
 */
class UserDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'device_token', 'client'], 'required'],
            [['client', 'puduct'], 'integer'],
            [['account_id', 'device_token'], 'string', 'max' => 64],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRelationshipRealestate::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'device_token' => '设备口令',
            'client' => 'Client',
            'puduct' => 'Puduct',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserRelationshipRealestate::className(), ['account_id' => 'account_id']);
    }
}
