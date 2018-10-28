<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/10/27
 * Time: 11:14
 */

namespace api\controllers;


use common\models\ShoppingAddress;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;

class ShoppingAddressController extends Controller
{
    //获取地址栏列表
    function actionIndex($account_id, $page)
    {
        $address = ShoppingAddress::find()
            ->select('id, name, phone, address')
            ->where(['account_id' => "$account_id"])
            ->orderBy('update_time');

        $count = $address->count(); //求总数
        $p = '10'; //每页获取条数
        $pa = ceil($count/$p); //求页数
        if($page>$pa){
            return false;
        }
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => "$p"]); //实例化分页模型并设置每页获取数量

        $address = $address->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if($address){
            $address = Json::encode($address);
            return $address;
        }

        return false;
    }

    //添加用户常用收货地址
    function actionAdd($id, $account_id, $name, $phone, $address)
    {
        if(!$id){ //如果id不存在则添加新记录
            $model = new ShoppingAddress(); //实例化模型

            $model->account_id = $account_id;
            $model->name = $name;
            $model->phone = $phone;
            $model->address = $address;

            $result = $model->save(); //保存数据
        }else{ //如果$id存在则更新记录
            $result = ShoppingAddress::updateAll(['name' => "$name", 'phone' => "$phone", 'address' => "$address"], 'id = :id and account_id = :a_id', [':id' => "$id", ':a_id' => "$account_id"]);
        }

        if($result)
        {
            return true;
        }
        return false;
    }
}