<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_reply".
 *
 * @property int $reply_id
 * @property int $ticket_id （工单编号）
 * @property string $account_id （用户ID）
 * @property string $content （反馈内容）
 * @property int $is_attachment （是否有附件）
 * @property int $reply_time （回复时间）
 * @property int $reply_status （状态，默认1-正常，2-删除）
 *
 * @property TicketBasic $ticket
 * @property UserAccount $account
 */
class TicketReply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_reply';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'account_id', 'content', 'is_attachment', 'reply_time'], 'required'],
            [['ticket_id', 'is_attachment', 'reply_status'], 'integer'],
            [['account_id'], 'string', 'max' => 64],
            [['content'], 'string', 'max' => 128],
            [['ticket_id', 'account_id', 'content'], 'unique', 'targetAttribute' => ['ticket_id', 'account_id', 'content']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketBasic::className(), 'targetAttribute' => ['ticket_id' => 'ticket_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reply_id' => '编号',
            'ticket_id' => '工单编号',
            'account_id' => '回复人',
            'content' => '详情',
            'is_attachment' => '附件',
            'reply_time' => '回复时间',
            'reply_status' => '回复状态',
        ];
    }
	
	//截取字符串
	public function getE()
	{
	    $tmpStr = strip_tags($this->content);
		$tmpLen = mb_strlen($tmpStr);
		
		return mb_substr($tmpStr,0,25,'utf-8').(($tmpLen>25)?'...':'');	
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getT()
    {
        return $this->hasOne(TicketBasic::className(), ['ticket_id' => 'ticket_id']);
    }
	
	//建立用户信息关联
	public function getD()
    {
        return $this->hasOne(UserData::className(), ['account_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getA()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
}
