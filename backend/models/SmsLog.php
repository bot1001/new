<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms_log".
 *
 * @property int $id
 * @property string $sign_name （短信模板名称）
 * @property string $sms （短信模板编号）
 * @property int $type （短信类型，0 => 验证码， 2 => 消息）
 * @property int $count （发送总数）
 * @property int $success （发送成功条数）
 * @property int $sms_time （发送时间）
 * @property string $property （备注）
 */
class SmsLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sign_name', 'sms', 'type', 'count', 'success', 'sms_time', 'property'], 'required'],
            [['type', 'count', 'success'], 'integer'],
            [['sign_name', 'sms', 'property'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sign_name' => '模板名称',
            'sms' => '模板编号',
            'type' => '类型',
            'count' => '总数',
            'success' => '成功',
            'sms_time' => '发送时间',
            'property' => '备注',
        ];
    }
}
