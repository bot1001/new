<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


class Login extends Model
{
	public static function Wx($code, $appid)
	{
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=dedd7bad5b2b3c43a8e23597dfa27698&code=$code&grant_type=authorization_code";
		
		$ch = curl_init();
        // 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);
        
        // 释放curl句柄
        curl_close($ch);
				
		return $output;
	}
	
	//微信获取用户信息
	public static function Info($token, $openid)
	{
		$user_info = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		
		$u = curl_init();
        // 设置选项，包括URL
        curl_setopt($u,CURLOPT_URL,$user_info);
        curl_setopt($u,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($u,CURLOPT_HEADER,0);
        // 执行并获取HTML文档内容
        $user = curl_exec($u);
        
        // 释放curl句柄
        curl_close($u);
				
		return $user;
	}
	
    public $mobile_phone;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile_phone', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
                $this->addError($attribute, '账户或密码错误');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->mobile_phone);
        }

        return $this->_user;
    }
}
