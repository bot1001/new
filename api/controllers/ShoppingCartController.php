<?php
/**
 * Created by PhpStorm.
 * User: 主管坐骑
 * Date: 2018/10/26
 * Time: 15:23
 */

namespace api\controllers;


use common\models\ShoppingCart;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class ShoppingCartController extends Controller
{
    //小程序查询购物车列表
    function actionIndex($account_id, $page)
    {
        $product = (new Query())
        ->select("product_basic.product_name as name, product_basic.product_subhead as subhead, product_basic.product_price as price,
         product_basic.product_status as status, shopping_cart.summation, (product_basic.product_price)*(shopping_cart.summation) as amount")
            ->from('shopping_cart')
            ->join('inner join', 'product_basic', 'product_basic.product_id = shopping_cart.product_id')
            ->andwhere(['shopping_cart.account_id' => $account_id])
            ->andWhere(['>', 'summation', '0']);

        $count = $product->count(); //求总记录数
        if($count == '0')
        {
            return false;
        }

        $p = '10';
        $pa = ceil($count/$p);
        if($page > $pa)
        {
            return false;
        }
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $p]); //实例化是设置分页
        $product = $product->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $product = Json::encode($product);
        return $product;
    }

    //购物车添加
    function actionAdd($account_id, $product_id, $count)
    {
        if($count <= '0') //判断添加数量是否大于等于零
        {
            return false;
        }
        $product = ShoppingCart::find()
            ->select('summation')
            ->where(['account_id' => "$account_id", 'product_id' => "$product_id"])
            ->asArray()
            ->one();

        if($product){ //如果购物车存在该产品，则更新
            $summation = $product['summation'] +$count;

            $add = ShoppingCart::updateAll(['summation' => "$summation"], 'account_id = :a_id and product_id = :p_id', [':a_id' => "$account_id", ':p_id' => "$product_id"]);
        }else{ //否则添加该产品
            $product = new ShoppingCart(); //实例化购物车

            $product->account_id = $account_id;
            $product->product_id = $product_id;
            $product->summation = $count;
            $product->update_time = time();

            $add = $product->save(); //保存用户数据
        }

        if($add)
        {
            return true;
        }
        return false;
    }
}