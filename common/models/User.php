<?php

namespace common\models;

class User extends /*\yii\base\Object*/ \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /*public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];
*/

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
            [['name', 'new_pd'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['authKey'], 'string', 'max' => 100],
            [['accessToken'], 'string', 'max' => 100],
        ];
    }

    /**
     * 表字段别名
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'real_name' => '真实姓名',
            'name' => '昵称',
			'role' => '角色',
			'company' => '公司',
			'community' => '关联小区',
            'password' => '旧密码',
            'status' => '状态',
            'comment' => '备注',
            'salt' => '密码盐',
            'create_id' => '创建者',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'update_id' => '操作人',
            'new_pd' => '新密码',
            'phone' => '联系方式',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
          $user = User::find()
            ->where(['name' => $username])
            ->asArray()
            ->one();

            if($user){
            return new static($user);
        }

        return null;
        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->new_pd === md5($password);
    }
}
