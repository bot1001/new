<?php
namespace api\controllers;

use common\models\User;
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
}
