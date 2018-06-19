<?php

namespace common\models;

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
	public $new_pd;
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
			[['new_pd', 'password'],'required'],
			[['mobile_phone'],'string','min' => 11],
			[['account_id'],'string','min' => 28,'max' => 32],
            [['password','mobile_phone'], 'required'],
			['new_pd', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码输入不一致'],
			[['mobile_phone', 'account_id'], 'unique', 'targetAttribute' => ['mobile_phone', 'account_id'], 'message' => '用户重复'],
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
            'new_pd' => '密码',
            'mobile_phone' => '手机号码',
            'qq_openid' => 'Qq Openid',
            'weixin_openid' => 'Weixin Openid',
            'wx_unionid' => 'wx_unionid',
            'weibo_openid' => 'Weibo Openid',
            'account_role' => '角色',
            'new_message' => 'New Message',
			'status' => '状态',
			'fromdate' => 'From','todate' => 'To',
        ];
    }
	
	public function beforeSave($insert)
    {
       if(parent::beforeSave($insert))
       {
           if($insert)
           {
               $this->account_role = 0;
               $this->new_message = 0;
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
}
