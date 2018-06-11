<?php

namespace app\models;
use mdm\admin\components\Configs;

use Yii;

/**
 * This is the model class for table "sys_user".
 *
 * @property integer $id
 * @property string $real_name
 * @property string $name
 * @property string $password
 * @property integer $status
 * @property string $comment
 * @property string $salt
 * @property integer $create_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $update_id
 * @property string $new_pd
 * @property string $phone
 */
class SysUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'phone', 'name', 'community', 'password', 'role', 'new_pd', 'n'], 'required', 'on' => 'create'],
            [['status', 'create_id', 'update_id', 'role'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['real_name', 'name', 'password', 'comment', 'salt'], 'string', 'max' => 100],
            [['new_pd'], 'string', 'min' => 6,'max' => 128],
            [['phone'], 'string', 'max' => 12],
            [['name'], 'unique'],
			[['name'], 'required', 'message' => '确认密码不能为空'],
            ['n', 'compare', 'compareAttribute' => 'new_pd', 'message' => '两次密码输入不一致'],
			
            [['community'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community' => 'community_id']],
            [['company'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company' => 'id']],
        ];
    }
	
	public $n;
	public $created_at;
	public $item_name;
	public $user_id;
		
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'company' => '隶属公司',
            'real_name' => '真实姓名',
            'name' => '名字',
			'role' => '数据库角色',
			'community' => '关联小区',
			'n' => '密码',
            'password' => '公司',
            'status' => '状态',
            'comment' => '备注',
            'salt' => '密码盐',
            'create_id' => '创建者',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'update_id' => '操作人',
            'new_pd' => '密码',
            'phone' => '联系方式',
        ];
    }
	
	//保存数据前自动插入数据
	public function beforeSave($insert) //public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//创建数据前自动插入以下字段
				$this->create_id = $_SESSION['user']['0']['id'];
				$this->create_time = date('Y-m-d h:i:s');
				$this->password = 'e10adc3949ba59abbe56e057f20f883e';
				$this->salt = 'e10adc3949ba59abbe56e057f20f883e';
				$this->update_id = $_SESSION['user']['0']['id'];
				$this->update_time = date('Y-m-d h:i:s');
			}else{
				$this->update_time = date('Y-m-d h:i:s');
				$this->update_id = $_SESSION['user']['0']['id'];
			}
			return true;
		}else{
			return false;
		}
	}
	
	//设置场景
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios['create'] = ['status', 'phone', 'name', 'community', 'password', 'role', 'new_pd', 'n'];
		return $scenarios;
	}
	
	 public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
		//查寻登录用户
		$user = (new \yii\db\Query())
			->from('sys_user')
			->join('inner join', 'auth_assignment', 'auth_assignment.user_id = sys_user.id')
			->where(['sys_user.name' => $username, 'sys_user.status' => '1'])
			->one();

            if($user){
            return new static($user);
        }
        return null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->new_pd === md5($password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getDb()
    {
        return Configs::userDb();
    }
	
	 public function getRo()
	 {
	     return $this->hasOne(SysRole::className(), ['id' => 'role']);
	 }
	
	public function getC()
	 {
	     return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community']);
	 }
	
	//关联公司
	public function getCom()
	{
	    return $this->hasOne(Company::className(), ['id' => 'company']);
	}
}
