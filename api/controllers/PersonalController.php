<?php

namespace api\controllers;

use common\models\Area;
use common\models\UserData;
use yii\web\Controller;

class PersonalController extends Controller
{
    function actionUpdate($account, $name, $gender, $face, $province, $city, $area, $nickname){
        //查询用户地址信息
        $address = Area::find()
            ->select('id, area_name')
            ->where(['in', 'area_name', [$province, $city, $area]])
            ->indexBy('area_name')
            ->orderBy('id')
            ->column();

        //修改数据
        $result = UserData::updateAll(['province_id' => $address["$province"], 'city_id' => $address["$city"], 'area_id' => $address["$area"],
            'gender' => $gender, 'real_name' => $name, 'face_path' => $face, 'nickname' => $nickname], 'account_id = :a_id', [':a_id' => $account]);

        if($result){
            return true;
        }
        return false; //默认返回空
    }
}