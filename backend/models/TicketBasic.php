<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_basic".
 *
 * @property int $ticket_id （工单ID，自增长）
 * @property string $ticket_number （工单编号，日期+4位随机数）
 * @property string $account_id （提交用户ID）
 * @property int $community_id （关联隶属小区ID）
 * @property int $realestate_id （关联房屋ID）
 * @property int $tickets_taxonomy （类型，关联ID）
 * @property string $explain1 （说明）
 * @property int $create_time （提交时间）
 * @property string $contact_person （联系人）
 * @property string $contact_phone （联系电话）
 * @property int $is_attachment （是否有附件）
 * @property string $assignee_id （工单接收人，关联物业人ID）
 * @property string $reply_total （回复数，用来判断是否有回复并做相应的布局显示）
 * @property string $ticket_status （状态） 	1:'待接单',2:'已接单',3:'已完成',4:'返修',5:'关闭',6,'处理中'
 * @property int $remind （提醒，0=>未提醒）
 *
 * @property CommunityBasic $community
 * @property CommunityRealestate $realestate
 * @property UserAccount $account
 * @property UserData $account0
 * @property TicketReply[] $ticketReplies
 */
class TicketBasic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_basic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_number', 'account_id', 'community_id', 'realestate_id', 'tickets_taxonomy', 'explain1', 'create_time', 'contact_person', 'contact_phone', 'is_attachment', 'assignee_id', 'reply_total', 'ticket_status'], 'required'],
            [['community_id', 'realestate_id', 'tickets_taxonomy', 'create_time', 'is_attachment', 'remind'], 'integer'],
            [['ticket_number'], 'string', 'max' => 32],
            [['account_id', 'assignee_id', 'reply_total', 'ticket_status'], 'string', 'max' => 64],
            [['explain1'], 'string', 'max' => 50],
            [['contact_person'], 'string', 'max' => 20],
            [['contact_phone'], 'string', 'max' => 11],
            [['account_id', 'community_id', 'realestate_id', 'explain1'], 'unique', 'targetAttribute' => ['account_id', 'community_id', 'realestate_id', 'explain1']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community_id' => 'community_id']],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

   public function attributeLabels()
    {
        return [
            'ticket_id' => '序号',
            'ticket_number' => '编号',
            'account_id' => '投诉人',
            'community_id' => '小区',
            'realestate_id' => '房号',
            'tickets_taxonomy' => '类型',
            'explain1' => '详情',
            'create_time' => '创建时间',
            'contact_person' => '联系人',
            'contact_phone' => '电话',
            'is_attachment' => '附件',
            'assignee_id' => '接单人',
            'reply_total' => '回复次数',
            'ticket_status' => '状态',
            'remind' => '提示次数',
        ];
    }
	
	public static function getPengdingCommentCount()
	{
	    $c = $_SESSION['user']['community'];
	    if(empty($c)){
	        return TicketBasic::find()->where(['ticket_status' =>1])->count();
	    }else{
	        return TicketBasic::find()->andwhere(['ticket_status' => 1])->andwhere(['community_id' => $c])->count();
	    }
	}
	
	public static function getTicket()
	{
	    $ticket = TicketBasic::find()
	        ->select('ticket_basic.create_time,   ticket_basic.realestate_id')
	        ->joinWith('r');
	    return $ticket;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getA()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAc()
    {
        return $this->hasOne(UserData::className(), ['account_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRep()
    {
        return $this->hasMany(TicketReply::className(), ['ticket_id' => 'ticket_id']);
    }
}
