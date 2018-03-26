<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_reply".
 *
 * @property integer $reply_id
 * @property integer $ticket_id
 * @property string $account_id
 * @property string $content
 * @property integer $is_attachment
 * @property integer $reply_time
 * @property integer $reply_status
 */
class TicketReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'account_id', 'content', 'is_attachment', 'reply_time'], 'required'],
            [['ticket_id', 'is_attachment', 'reply_time', 'reply_status'], 'integer'],
            [['account_id'], 'string', 'max' => 64],
            [['content'], 'string', 'max' => 128],
            [['ticket_id', 'account_id', 'content'], 'unique', 'targetAttribute' => ['ticket_id', 'account_id', 'content'], 'message' => 'The combination of Ticket ID, Account ID and Content has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reply_id' => 'Reply ID',
            'ticket_id' => 'Ticket ID',
            'account_id' => 'Account ID',
            'content' => 'Content',
            'is_attachment' => 'Is Attachment',
            'reply_time' => 'Reply Time',
            'reply_status' => 'Reply Status',
        ];
    }
}
