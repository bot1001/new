<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property string $sign_name （模板名称）
 * @property string $sms （模板编号）
 * @property int $count （变量数量）
 * @property int $creator （创建人ID）
 * @property int $create_time （创建时间）
 * @property string $property （备注）
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sign_name', 'sms', 'count', 'creator', 'create_time', 'property'], 'required'],
            [['count', 'creator', 'create_time'], 'integer'],
            [['sign_name', 'sms', 'property'], 'string', 'max' => 50],
            [['sign_name', 'sms', 'count'], 'unique', 'targetAttribute' => ['sign_name', 'sms', 'count']],
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
            'count' => '变量总数',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'property' => '备注',
        ];
    }
}
