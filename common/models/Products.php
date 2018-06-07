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
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_quantity' => 'Product Quantity',
            'store_id' => 'Store ID',
            'product_name' => 'Product Name',
            'sale' => 'Sale',
            'product_price' => 'Product Price',
        ];
    }
}
