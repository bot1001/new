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
 * @property string $property 备注
 *
 * @property ProductBasic $product
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
//            [['product_id', 'summation', 'update_time'], 'integer'],
//            [['account_id', 'property'], 'string', 'max' => 50],
//            [['account_id', 'product_id'], 'unique', 'targetAttribute' => ['account_id', 'product_id']],
//            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'product_id']],
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
            'account_id' => 'Account ID',
            'product_id' => 'Product ID',
            'summation' => '数量',
            'update_time' => '更新时间',
            'property' => '备注',
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
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
}
