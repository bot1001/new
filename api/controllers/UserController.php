<?php
namespace api\controllers;

use common\models\User;
use common\models\UserAccount;
use common\models\UserRealestate;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class UserController extends Controller
{
    public function actionInfo($name, $password)
    {
        $info = User::find()
            ->where(['name' => "$name", 'new_pd' => md5($password)])
            ->asArray()
            ->one();

        $info = Json::encode($info); //数组转换

        return $info; //返回数组
    }

    //裕家人小程序修改密码
    function actionChange($account_id, $old_p, $new_p)
    {
        $account = UserAccount::find() //查找用户账户信息
            ->select('password')
            ->where(['account_id' => "$account_id"])
            ->asArray()
            ->one();

        if(md5($old_p) == $account['password']) //判断新旧密码是否相等
        {
            //更新旧密码
            $result = UserAccount::updateAll(['password' => md5($new_p)], 'account_id = :a_id', [':a_id' => $account_id]);
            if($result){
                return true;
            }
            return false;
        }

        return false; // 默认返回值
    }

    //小程序关联房屋
    function actionRelevancy($account_id, $realestate_id)
    {
        $model = new UserRealestate(); //实例化模型

        $model->account_id = $account_id;
        $model->realestate_id = $realestate_id;

        $result = $model->save(); //模型保存数据

        if($result)
        {
            return true; //如果保存成功则返回true
        }
        return false; //默认返回值
    }

    //小程序解绑房屋
    function actionUntie($account_id, $realestate_id)
    {
        $home_relation = (new \yii\db\Query()) //查找用户关联房号
            ->select('id')
            ->from('user_relationship_realestate')
            ->where(['account_id' => $account_id, 'realestate_id' => $realestate_id])
            ->one();

        if($home_relation) //判断关联房号是否存在
        {
            $result = UserRealestate::findOne($home_relation['id'])->delete();
            if($result){
                return true;
            }
            return false; //默认返回值
        }

        return false; //默认返回值
    }
}
