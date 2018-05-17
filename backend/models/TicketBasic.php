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
            [['community_id', 'realestate_id', 'tickets_taxonomy', 'explain1', 'contact_person', 'contact_phone', 'assignee_id', 'ticket_status'], 'required'],
            [['community_id', 'realestate_id', 'tickets_taxonomy', 'is_attachment', 'remind'], 'integer'],
            [['ticket_number'], 'string', 'max' => 32],
			['create_time', function($attr, $params) {
                if ($this->hasErrors()) return false;

                $datetime = $this->{$attr};
                $time = strtotime($datetime);
                // 验证时间格式是否正确
                if ($time === false) {
                    $this->addError($attr, '时间格式错误.');
                    return false;
                }
                // 将转换为时间戳后的时间赋值给time属性
                $this->{$attr} = $time;
                return true;
            }],
            [['account_id', 'assignee_id', 'reply_total', 'ticket_status'], 'string', 'max' => 64],
            [['explain1'], 'string', 'max' => 50],
            [['contact_person'], 'string', 'max' => 20],
            [['contact_phone'], 'integer', 'max' => 12],
            [['account_id', 'community_id', 'realestate_id', 'explain1'], 'unique', 'targetAttribute' => ['account_id', 'community_id', 'realestate_id', 'explain1']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community_id' => 'community_id']],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
//            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
//            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }
	
	public $building;
	public $number;

   public function attributeLabels()
    {
        return [
            'ticket_id' => '序号',
            'ticket_number' => '编号',
            'account_id' => '投诉人',
            'community_id' => '小区',
            'building' => '楼宇',
            'number' => '单元',
            'realestate_id' => '房号',
            'tickets_taxonomy' => '类型',
            'explain1' => '详情',
            'create_time' => '投诉时间',
            'contact_person' => '联系人',
            'contact_phone' => '电话',
            'is_attachment' => '附件',
            'assignee_id' => '处理人',
            'reply_total' => '回复次数',
            'ticket_status' => '状态',
            'remind' => '提示次数',
        ];
    }
	
	public $replay;
	
	//数据保存之前自动插入字段
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->account_id = $_SESSION['user']['0']['id'];
				$this->create_time = date(time());
				$this->ticket_status = '1';
				$this->reply_total = '0';
				$this->is_attachment = '0';
			}else{
				$this->create_time = $this->create_time;
			}
			return true;
		}
		else{
			return false;
		}
	}
	
	//截取字符串
	public function getE()
	{
	    $tmpStr = strip_tags($this->explain1);
		$tmpLen = mb_strlen($tmpStr);
		
		return mb_substr($tmpStr,0,20,'utf-8').(($tmpLen>20)?'...':'');	
	}
	
	//输出时间转换
	public function afterFind()
    {
        parent::afterFind();
        $this->create_time = date('Y-m-d h:i:s', $this->create_time);
    }
	
	public static function getPengdingCommentCount()
	{
	    $c = $_SESSION['community'];
	    return TicketBasic::find()->andwhere(['ticket_status' => 1])->andwhere(['in', 'community_id', $c])->count();
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
    public function getB()
    {
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building_id'])->viaTable('community_realestate', ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getA()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'assignee_id']);
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
