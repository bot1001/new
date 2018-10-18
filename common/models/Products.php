<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property int $id
 * @property string $order_id （订单号）
 * @property string $product_id （商品ID）
 * @property int $product_quantity （商品数量）
 * @property string $store_id （关联商店ID）
 * @property string $product_name （商品名称）
 * @property int $sale （优惠）
 * @property string $product_price （商品价格）
 */
class Products extends \yii\db\ActiveRecord
{
    //自定义变量
    public $phone;
//    public $address;
    public $add;
    public $name;
    public $create_time;
    public $payment_time;
    public $status;
    public $amount;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'product_quantity'], 'required'],
            [['product_quantity', 'sale'], 'integer'],
            [['product_price'], 'number'],
            [['order_id', 'product_id', 'product_name'], 'string', 'max' => 64],
            [['store_id'], 'string', 'max' => 10],
            [['order_id', 'product_id'], 'unique', 'targetAttribute' => ['order_id', 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'product_id' => '商品编号',
            'product_quantity' => '数量',
            'store_id' => '商店编号',
            'product_name' => '名称',
            'sale' => '优惠',
            'product_price' => '单价',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(), ['store_id' => 'store_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    function getAddress()
    {
        return $this->hasOne(OrderAddress::className(), ['order_id' => 'order_id']);
    }
}
