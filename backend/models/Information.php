<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "information".
 *
 * @property int $remind_id （序号）
 * @property int $room_name （房号）
 * @property string $detail （详情）
 * @property int $times （提醒次数）
 * @property int $reading （是否已读，0=未读）
 * @property int $target （提醒对象）
 * @property int $ticket_number （投诉序号）
 * @property string $remind_time （提醒时间）
 * @property string $property （备注）
 *
 * @property CommunityRealestate $roomName
 * @property TicketBasic $ticketNumber
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
            [['room_name', 'times', 'target', 'ticket_number', 'remind_time', 'property'], 'required'],
            [['room_name', 'times', 'reading', 'target', 'ticket_number', 'remind_time'], 'integer'],
            [['detail', 'property'], 'string', 'max' => 50],
            [['room_name'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['room_name' => 'realestate_id']],
            [['ticket_number'], 'exist', 'skipOnError' => true, 'targetClass' => TicketBasic::className(), 'targetAttribute' => ['ticket_number' => 'ticket_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'remind_id' => '序号',
            'room_name' => '房号',
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
    public function getRoom()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'room_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(TicketBasic::className(), ['ticket_id' => 'ticket_number']);
    }
}
