<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_property".
 *
 * @property int $id （属性ID）
 * @property int $product_id （产品ID）
 * @property string $price （价格）
 * @property string $size （产品尺寸）
 * @property string $color （产品颜色）
 * @property string $image （缩略图）
 * @property int $quantity （库存量）
 *
 * @property ProductBasic $product
 * @property ShoppingCart[] $shoppingCarts
 */
class ProductProperty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'size', 'color', 'image', 'quantity'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['size', 'color'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 128],
            [['product_id', 'size', 'color'], 'unique', 'targetAttribute' => ['product_id', 'size', 'color']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'price' => 'Price',
            'size' => 'Size',
            'color' => 'Color',
            'image' => 'Image',
            'quantity' => 'Quantity',
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
    public function getShoppingCarts()
    {
        return $this->hasMany(ShoppingCart::className(), ['product_id' => 'product_id']);
    }
}
