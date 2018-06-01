<?php  
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
  
class User extends ActiveRecord 
{ 
	
	public $rememberMe = true;
	
    public static function tableName(){  
        return 'user_account';  
    }  
  
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_phone'], 'required', 'message' => '账户不能为空'],
            [['password'], 'required', 'message' => '密码不能为空'],
            [['rememberMe'], 'boolean'],
			['userpass', 'validatePass']
        ];
    }
	
	
	public function attributeLabels()
    {
        return [
            'mobile_phone' => '登录号码',
            'password' => '登录密码',
            'rememberMe' => '记住我',
        ];
    }
	
	public function validatePass()
	{
		if($this->hassErrors()){
			$data = self::find()->where(['mobile_phone = :phone and password = :password', [":phone" => $this->mobile_phone, ':password' => md5($this->password)]])->one();
			if(is_null($data)){
				$this->addError("userpass", "用户名或者密码错误");
			}
		}
	}
	
	public function login()
	{
		if($this->load($data) && $this->validate())
		{
			//执行登录
			 
		}
		return false;
	}
}
?>  