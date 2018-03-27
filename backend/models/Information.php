<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "information".
 *
 * @property int $remind_id （序号）
 * @property int $community （房号 => 小区）
 * @property string $detail （详情）
 * @property int $times （提醒次数）
 * @property int $reading （是否已读，0=未读）
 * @property string $target （提醒对象）
 * @property string $ticket_number （投诉序号）
 * @property string $remind_time （提醒时间）
 * @property string $property （备注）
 *
 * @property CommunityBasic $community0
 */
class Information extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'information';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community', 'times', 'target', 'ticket_number', 'remind_time'], 'required'],
            [['community', 'times', 'reading', 'remind_time'], 'integer'],
            [['detail', 'target', 'property'], 'string', 'max' => 50],
            [['ticket_number'], 'string', 'max' => 128],
            [['ticket_number'], 'unique'],
            [['community'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community' => 'community_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'remind_id' => '序号',
            'community' => '小区',
            'detail' => '详情',
            'times' => '提醒次数',
            'reading' => '已读',
            'target' => '接收者',
            'ticket_number' => '订单编号',
            'remind_time' => '提醒时间',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community']);
    }
}
