<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_basic".
 *
 * @property int $product_id （产品ID）
 * @property int $store_id （关联商店ID）
 * @property string $product_name （商品名称）
 * @property string $product_subhead （商品副标题）
 * @property string $product_taxonomy （产品分类）
 * @property int $brand_id （品牌ID）
 * @property string $market_price （市场价格，预留字段）
 * @property string $product_price （商品价格）
 * @property string $product_image （商品缩略图）
 * @property string $product_introduction （商品介绍）
 * @property int $product_quantity （库存量）
 * @property int $product_status （产品状态，1-上架，2-下架）
 *
 * @property StoreBasic $store
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_basic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'product_name', 'product_subhead', 'market_price', 'product_price', 'product_image', 'product_introduction', 'product_quantity', 'product_status'], 'required'],
            [['store_id', 'brand_id', 'product_quantity', 'product_status'], 'integer'],
            [['market_price', 'product_price'], 'number'],
            [['product_name', 'product_subhead', 'product_taxonomy'], 'string', 'max' => 64],
            [['product_image'], 'string', 'max' => 300],
            [['product_introduction'], 'string', 'max' => 20000],
            [['store_id', 'product_name'], 'unique', 'targetAttribute' => ['store_id', 'product_name']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'store_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => '序号',
            'store_id' => '商店',
            'product_name' => '名称',
            'product_subhead' => '副标题',
            'product_taxonomy' => '品牌系列',
            'brand_id' => '品牌',
            'market_price' => '市场价',
            'product_price' => '当前价',
            'product_image' => '缩略图',
            'product_introduction' => '商品详情',
            'product_quantity' => '库存量',
            'product_status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['store_id' => 'store_id']);
    }
}
