<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_client".
 *
 * @property int $id
 * @property int $user_id （客户ID）
 * @property int $type （短信类型：0=>手动;2=>自动）
 * @property int $community （小区：自动发送的时候才用到）
 * @property int $count （短信总量）
 * @property int $surplus （短信余量）
 * @property int $status （状态，0=>停止，1=>正常）
 * @property string $property （备注）
 *
 * @property SysUser $user
 */
class SmsClient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'community', 'count', 'surplus', 'status', 'property'], 'required'],
            [['user_id', 'type', 'community', 'count', 'surplus', 'status'], 'integer'],
            [['property'], 'string', 'max' => 50],
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
            'user_id' => '商户',
            'type' => '类型',
            'community' => '预发送小区',
            'count' => '短信总量',
            'surplus' => '短信余量',
            'status' => '状态',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
