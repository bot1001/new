<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Site extends ActiveRecord
{
	
    public static function getK()
	{
       $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $k = "";
       for($i=0;$i<32;$i++)
        {
            $k .= $str{mt_rand(0,32)}; //生成php随机数
        }
        return $k;
    }
}
