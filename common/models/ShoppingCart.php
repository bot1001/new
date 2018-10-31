<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shopping_cart".
 *
 * @property int $id 购物车
 * @property string $account_id 用户ID
 * @property int $product_id 产品ID
 * @property int $summation 数量
 * @property int $update_time 更新时间
 * @property int $property 商品属性
 *
 * @property ProductBasic $product
 * @property ProductProperty $property0
 * @property UserAccount $account
 */
class ShoppingCart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shopping_cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['account_id', 'product_id', 'summation', 'update_time'], 'required'],
//            [['product_id', 'summation', 'update_time', 'property'], 'integer'],
//            [['account_id'], 'string', 'max' => 50],
//            [['account_id', 'product_id', 'property'], 'unique', 'targetAttribute' => ['account_id', 'product_id', 'property']],
//            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'product_id']],
//            [['property'], 'exist', 'skipOnError' => true, 'targetClass' => ProductProperty::className(), 'targetAttribute' => ['property' => 'id']],
//            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => '用户 ID',
            'product_id' => '产品id',
            'summation' => '数量',
            'update_time' => '更新时间',
            'property' => '产品属性',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty0()
    {
        return $this->hasOne(ProductProperty::className(), ['id' => 'property']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
}
