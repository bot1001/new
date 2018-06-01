<?php  
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
  
class User extends ActiveRecord 
{ 
	
	public $rememberMe = true;
	public $userpass;
	
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
		if(!$this->hasErrors()){
			$data = self::find()
				->where(['mobile_phone' => $this->mobile_phone, 'password' => md5($this->password), 'status' => '1'])
				->asArray()
				->one();

			if($data == ''){
				$this->addError("userpass", "用户名或者密码错误");
				return false;
			}else{
				$session = Yii::$app->session;
			    $session['info'] = $data;
				return true;
			}
		}
	}
	
	public function login($data)
	{
		if($this->load($data) && $this->validatePass())
		{
			//执行登录
			
			$lifetime = $this->rememberMe ? 24*3600:0;
			$session = Yii::$app->session;
			session_set_cookie_params($lifetime);
			$session['user'] = ['isLogin' => 1];
			return (bool)$session['user']['isLogin'];
		}else{
			return false;
		}
	}
}
?>  