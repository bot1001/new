<?php

namespace common\models;

use Yii;
use yii\helpers\Json;
use common\models\Products;
use common\models\OrderProducts;
use common\models\OrderAddress as Address;

class Api extends \yii\db\ActiveRecord
{
   static function info($unionid)
   {
       //查询用户信息
       $info = (new \yii\db\Query())
           ->select('user_account.*, user_relationship_realestate.realestate_id, user_data.*')
           ->from('user_account')
           ->join('inner join' , 'user_relationship_realestate', 'user_relationship_realestate.account_id = user_account.account_id')
           ->join('inner join' , 'user_data', 'user_data.account_id = user_account.account_id')
           ->where(['user_account.wx_unionid' => "$unionid"])
           ->one();
       $info = Json::encode($info);

       return $info;
   }

   //生成订单
    static function Order($realestate, $account)
    {
        $user = (new \yii\db\Query()) // 查询用户信息
            ->select('user_data.real_name, user_data.province_id, user_data.city_id, user_data.area_id, user_account.mobile_phone')
            ->from('user_account')
            ->join('inner join', 'user_data', 'user_account.account_id = user_data.account_id')
            ->where(['user_account.account_id' => "$account"])
            ->one();

        $invoice = Invoice::find()//查询缴费数据
            ->where(['user_invoice.invoice_status' => '0', 'user_invoice.realestate_id' => "$realestate"])
            ->asArray()
            ->all();

        $cost_name = Cost::find()//查找优惠费项
            ->select('cost_name as cost, cost_id as id')
            ->where(['sale' => '1'])
            ->distinct()
            ->indexBy('id')
            ->column();

        $order_id = Order::getOrder(); //生成订单函数

        $house = (new \yii\db\Query())
            ->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as room')
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['community_realestate.realestate_id' => "$realestate"])
            ->one();

        $invoice_amount = array_column($invoice, 'invoice_amount'); //提取缴费金额
        $amount = array_sum($invoice_amount); //金额求和

        $account_id = $account;
        $type = '1'; //物业订单
        $description = '物业缴费';

        $name = $user['real_name']; //下单人
        $phone = $user['mobile_phone']; //手机号码
        $address = $house['community'].' '.$house['building'].' '.$house['number'].'单元'.' '.$house['room'].'号'; //订单地址
        $province = $user['province_id'];
        $city = $user['city_id'];
        $area = $user['area_id'];

        $transaction = Yii::$app->db->beginTransaction(); //标记事务
        try{
             $s = 0;//优惠计数
             $a = 0; //优惠金额
             foreach ($invoice as $in)
             {
                 $product = new Products();
                 $product->order_id = $order_id;
                 $product->product_id =$in['invoice_id'];

                 if(isset($cost_name[$in['description']]))
                 {
                     if($in['year'] > date('Y'))
                     {
                         $s ++; //标记预交物业费
                     }elseif($in['year'] == date('Y')){
                         if($in['month'] > date('m'))
                         {
                             $s ++; //标记预交物业费信息
                         }
                         }
                 }
                 if($s%13 === "0")
                 {
                     $product->sale = 1;
                     $a += $in['invoice_amount'];
                 }else{
                     $product->sale = '0';
                 }

                 $product->product_quantity = '1';
                 $p = $product->save(); //保存产品订单
             }
             $model = new Order(); //实例化订单模型

            $am = $amount-$a;

             $model->account_id = $account_id;
             $model->order_id = $order_id;
             $model->create_time = time();
             $model->order_type = $type;
             $model->description = $description;
             $model->order_amount = $am;

             $e = $model->save(); //保存

             $add = new Address(); //实例化订单地址模型

             $add->order_id = $order_id;
             $add->address = $address;
             $add->mobile_phone = $phone;
             $add->name = $name;
             $add->province_id = $province;
             $add->city_id = $city;
             $add->area_id = $area;

             $a = $add->save(); //保存

             if($p && $e && $a){
                 $transaction->commit(); //提交事务
             }else{
                 $transaction->rollback(); //滚回事务
                 return false;
             }
        }catch(\Exception $e) {
            $transaction->rollback(); //滚回事务
            return false;
        }
        return $order_id;
    }
}
