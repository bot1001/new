<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_account".
 *
 * @property integer $user_id
 * @property string $account_id
 * @property string $user_name
 * @property string $password
 * @property string $mobile_phone
 * @property string $qq_openid
 * @property string $weixin_openid
 * @property string $weibo_openid
 * @property integer $account_role
 * @property integer $new_message
 * @property integer $status
 *
 * @property OrderBasic[] $orderBasics
 */
class UserAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['mobile_phone'],'string','min' => 11],
			[['account_id'],'string','length' => 32],
			[['account_id'],'string','max' => 32],
            [['account_id','mobile_phone'], 'required'],
			['password', 'default', 'value' => 'e10adc3949ba59abbe56e057f20f883e'],
			[['account_id'], 'unique', 'targetAttribute' => ['account_id'], 'message' => '用户重复'],
            [['account_role', 'new_message', 'status'], 'integer'],
            [['account_id', 'user_name', 'password', 'qq_openid', 'weixin_openid', 'weibo_openid'], 'string', 'max' => 64],
            [['mobile_phone'], 'string', 'max' => 11],
        ];
    }
	
	public $fromdate;
	public $todate;
	public $k;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'account_id' => '用户_ID',
            'user_name' => '名字',
            'password' => '密码',
            'mobile_phone' => '手机号码',
            'qq_openid' => 'Qq Openid',
            'weixin_openid' => 'Weixin Openid',
            'weibo_openid' => 'Weibo Openid',
            'account_role' => 'Account Role',
            'new_message' => 'New Message',
			'status' => '状态',
			'fromdate' => 'From','todate' => 'To',
        ];
    }
	
	public function beforeSave($insert)
    {
       if(parent::beforeSave($insert))
       {
		   //保存 之前自动插入
           if($insert)
           {
               $this->new_message = 0;
               $this->account_role = 1;
               $this->status = 1;
			   $this->property = 1;
           }
           return true;
       }else{
       return false;
       }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderBasics()
    {
        return $this->hasMany(OrderBasic::className(), ['account_id' => 'account_id']);
    }
	
	//内部员工表格
	public function getWork()
    {
        return $this->hasOne(WorkR::className(), ['account_id' => 'account_id']);
    }
}
