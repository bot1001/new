<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property integer $id
 * @property string $order_id
 * @property string $product_id
 * @property integer $product_quantity
 * @property string $store_id
 * @property string $product_name
 * @property string $product_price
 *
 * @property OrderBasic $orderBasic
 * @property OrderRelationshipAddress[] $orders
 * @property UserInvoice[] $orders0
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'product_quantity', 'store_id', 'product_name', 'product_price'], 'required'],
            [['product_quantity'], 'integer'],
            [['product_price'], 'number'],
            [['order_id', 'product_id', 'product_name'], 'string', 'max' => 64],
            [['store_id'], 'string', 'max' => 10],
            [['order_id', 'product_id'], 'unique', 'targetAttribute' => ['order_id', 'product_id'], 'message' => 'The combination of Order ID and Product ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'product_id' => 'Product ID',
            'product_quantity' => '数量',
            'store_id' => '商城编号',
            'product_name' => '名称',
            'product_price' => '价格',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderBasic()
    {
        return $this->hasOne(OrderBasic::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(OrderRelationshipAddress::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(UserInvoice::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }
}
