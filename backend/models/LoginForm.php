<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $name;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name and password are both required
            [['name', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

	public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'name' => '用户名',
            'password' => '密码',
			'rememberMe' => '记住我'
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }

    /**
     * Logs in a user using the provided name and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) 
		{			
			return Yii::$app->user->login($this->getUser(), $this->rememberMe); //返回用户和缓存有效时间
        }
        return false;
    }

    /**
     * Finds user by [[name]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = SysUser::findByUsername($this->name);
        }
        return $this->_user;
    }
}
