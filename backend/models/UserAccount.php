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
	
	public $company;
	public $community;
	public $building;
	public $number;
	public $fromdate;
	public $k;
	public $gender;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'account_id' => '用户_ID',
            'user_name' => '名字',
            'gender' => '性别',
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
	
	public static function getUser($one, $two, $a)
	{
		$query = (new \yii\db\Query())->select([
			    'user_data.reg_time',
			    'community_realestate.community_id'])
			->from ('user_data')
			->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_data.account_id')
			->join('inner join','community_realestate','community_realestate.realestate_id =user_relationship_realestate.realestate_id')
			->andwhere(['between', 'user_data.reg_time', $one, $two])
		    ->andwhere(['in', 'community_realestate.community_id', $a]);
		
		return $query;
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

    //内部员工表格
	public function getData()
    {
        return $this->hasOne(UserData::className(), ['account_id' => 'account_id']);
    }

    //数组
    static function getAccount()
    {
        //获取用户绑定的小区
        $c = $_SESSION['community'];

        $account = $assignee = (new \yii\db\Query())
            ->select('user_account.user_name, user_account.account_id')
            ->from('user_account')
            ->join('inner join', 'work_relationship_account', 'work_relationship_account.account_id = user_account.account_id')
            ->andwhere(['user_account.status' => '1'])
            ->andwhere(['in', 'work_relationship_account.community_id', $c])
            ->orderBy('community_id')
            ->indexBy('account_id')
            ->column();

        return $account;
    }
}
