<?php

namespace common\models;

use Yii;

class User extends /*\yii\base\Object*/ \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_user';
    }

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
	
	public function setPassword($password)
    {
        return $this->new_pd === md5($password);
    }
	
	public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
