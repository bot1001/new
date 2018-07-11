<?php

namespace common\models;

use Yii;

class Ticket extends \yii\db\ActiveRecord
{
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
            [['contact_phone'],  'string', 'length' => [11, 12]],
            [['account_id', 'community_id', 'realestate_id', 'explain1'], 'unique', 'targetAttribute' => ['account_id', 'community_id', 'realestate_id', 'explain1']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community_id' => 'community_id']],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
        ];
    }

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
}
