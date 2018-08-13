<?php
namespace api\controllers;

use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class LimitController extends Controller
{
    public function actionLimit($url, $name) //权限验证接口
    {
        $url = urldecode($url); //逆向编码取回正确路由
        $limit = (new \yii\db\Query())
            ->from('auth_item_child')
            ->andwhere(['parent' => "$name"])
            ->andWhere(['child' => $url])
            ->one();

        if($limit) //判断用户权限是否存在
        {
            $limit = '1';
        }else{
            $limit = '0';
        }
        $limit = Json::encode($limit);

        return $limit;
    }
}
