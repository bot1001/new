<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work_relationship_account".
 *
 * @property int $id
 * @property string $account_id （账户唯一ID，MD5生成）
 * @property int $work_number （工号）
 * @property int $community_id （关联隶属小区ID）
 * @property int $account_superior （上司账户ID，默认0）
 * @property string $work_status （工作状态） 	空闲 	忙碌 	休假
 * @property string $account_role （角色）
 * @property string $account_status （状态）
 *
 * @property CommunityBasic $community
 * @property UserAccount $account
 */
class WorkR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_relationship_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_number', 'community_id', 'account_id'], 'required'],
            [['work_number', 'community_id', 'account_superior'], 'integer'],
            [['account_id'], 'string', 'max' => 32],
            [['work_status', 'account_role', 'account_status'], 'string', 'max' => 64],
            [['account_id', 'community_id'], 'unique', 'targetAttribute' => ['account_id', 'community_id']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community_id' => 'community_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'account_id' => '用户',
            'work_number' => '工号',
            'community_id' => '小区',
            'account_superior' => '上级',
            'work_status' => '工作状态',
            'account_role' => '操作权限',
            'account_status' => '使用状态',
        ];
    }
	
	//自动操作
	public function beforeSave($insert)
    {
       if(parent::beforeSave($insert))
       {
           if($insert)
           {
               $this->account_role = '1001,1002,1003,1004,1005,2001,2002,2003,2004,2005,3000';
			   $this->work_status = '1';
			   $this->account_superior = '0';
			   $this->account_status = '1';
           }
           return true;
       }else{
       return false;
       }
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
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
	
	public function getData() 
   { 
       return $this->hasOne(UserData::className(), ['account_id' => 'account_id']); 
   } 
}
