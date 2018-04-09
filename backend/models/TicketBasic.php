<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_basic".
 *
 * @property integer $ticket_id
 * @property string $ticket_number
 * @property string $account_id
 * @property integer $community_id
 * @property integer $realestate_id
 * @property integer $tickets_taxonomy
 * @property string $explain1
 * @property integer $create_time
 * @property string $contact_person
 * @property string $contact_phone
 * @property integer $is_attachment
 * @property string $assignee_id
 * @property string $reply_total
 * @property string $ticket_status
 */
class TicketBasic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_basic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_id', 'realestate_id', 'tickets_taxonomy', 'explain1', 'contact_person', 'contact_phone'], 'required'],
            [['community_id', 'realestate_id', 'tickets_taxonomy', 'create_time', 'is_attachment'], 'integer'],
            [['ticket_number'], 'string', 'max' => 32],
            [['account_id', 'assignee_id', 'reply_total', 'ticket_status'], 'string', 'max' => 64],
            [['explain1'], 'string', 'max' => 50],
            [['contact_person'], 'string', 'max' => 20],
            [['contact_phone'], 'string', 'max' => 11],
            [['account_id', 'community_id', 'realestate_id', 'explain1'], 'unique', 'targetAttribute' => ['account_id', 'community_id', 'realestate_id', 'explain1'], 'message' => 'The combination of Account ID, Community ID, Realestate ID and Explain1 has already been taken.'],
        ];
    }

	public function getBeginning()
	{
	    $tmpStr = strip_tags($this->ticket_basic.explain1);
		$tmpLen = mb_strlen($tmpStr);
		
		return mb_substr($tmpStr,0,11,'utf-8').(($tmpLen>11)?'...':'');	
	}
    /**
     * @inheritdoc
     */
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
            'contact_phone' => '手机号码',
            'is_attachment' => '附件',
            'assignee_id' => '接单人',
            'reply_total' => '回复次数',
            'ticket_status' => '状态',
			'remind' => '提醒'
        ];
    }
	
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->account_id = $_SESSION['user']['community'];
				$this->create_time = date(time());
				$this->ticket_status = '1';
				$this->reply_total = '0';
				$this->is_attachment = '0';
			}
			return true;
		}
		else{
			return false;
		}
	}
	
	// 建立管理小区
	public function getC()
    {
        return $this->hasMany(CommunityBasic::className(), ['community_id' => 'community_id']);
    }
	// 建立关联房屋
	public function getR()
   {
       return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
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
			->select('ticket_basic.create_time,	ticket_basic.realestate_id')
			->joinWith('r');
		return $ticket;
	}
}
