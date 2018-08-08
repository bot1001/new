<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_openid".
 *
 * @property int $id （序号）
 * @property string $account_id （用户ID）
 * @property string $open_id （平台open_id)
 * @property int $type （类型，1=>公众号，2=>小程序裕家人）
 * @property string $property （备注）
 *
 * @property UserAccount $account
 */
class UserOpenid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_openid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'open_id', 'type'], 'required'],
            [['type'], 'integer'],
            [['account_id', 'open_id', 'property'], 'string', 'max' => 50],
            [['account_id', 'open_id'], 'unique', 'targetAttribute' => ['account_id', 'open_id']],
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
            'open_id' => 'Open ID',
            'type' => 'Type',
            'property' => 'Property',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }

    static function unionid($account_id)
    {
        $unionid = self::find()
            ->where(['account_id' => "$account_id"])
            ->one();
        if($unionid){
            return true;
        }else{
            return false;
        }
    }
}
