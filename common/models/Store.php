<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_basic".
 *
 * @property int $store_id （商店ID）
 * @property string $store_cover （商店缩略图）
 * @property int $province_id （所在省份）
 * @property int $city_id （所在城市）
 * @property int $area_id （所在区/县）
 * @property string $store_name （商店名称）
 * @property string $community_id   超市关联小区id
 * @property string $store_address （商店地址）
 * @property string $store_introduce （商店介绍）
 * @property string $store_phone （商店电话）
 * @property double $store_longitude （经度）
 * @property double $store_latitude （纬度）
 * @property int $add_time （录入时间）
 * @property int $is_certificate （是否认证，默认0-未认证）
 * @property int $store_sort （排序，默认0，预留字段）
 * @property string $store_status （状态,1启用,0禁用）
 * @property int $type 类型,1,超市,2,商店
 * @property int $store_taxonomy
 *
 * @property ProductBasic[] $productBasics
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_basic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_cover', 'province_id', 'city_id', 'area_id', 'store_name', 'store_address', 'store_introduce', 'store_phone', 'store_status', 'type'], 'required'],
            [['add_time'], function($model){
                if ($this->hasErrors()) return false;
                $datetime = $this->{$model};
                $time = strtotime($datetime);
                if($time == false){
                    $this->addError($model, '时间格式错误');
                    return false;
                }
                $this->{$model} = $time;
                return true;
            }],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['province_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['store_taxonomy'], 'exist', 'skipOnError' => true, 'targetClass' => StoreTaxonomy::className(), 'targetAttribute' => ['store_taxonomy' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'store_id' => '编号',
            'store_cover' => '缩略图',
            'province_id' => '省份',
            'city_id' => '城市',
            'area_id' => '地区',
            'store_name' => '名称',
            'community_id' => 'Community ID',
            'store_address' => '地址',
            'store_introduce' => '介绍',
            'store_phone' => '联系方式',
            'store_longitude' => '经度',
            'store_latitude' => '维度',
            'add_time' => '创建时间',
            'is_certificate' => '认证',
            'store_sort' => '商城位置',
            'store_status' => '状态',
            'type' => '类型',
            'store_taxonomy' => '行业',
        ];
    }

    //时间转换
    public function afterFind()
    {
        parent::afterFind(); // 继承父级搜索
        $this->add_time = date('Y:m:d H:i:s', $this->add_time);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasMany(Product::className(), ['store_id' => 'store_id']);
    }

    //获取商店
    static function getStore()
    {
        $store = self::find()
            ->select('store_name, store_id')
            ->indexBy('store_id')
            ->column();

        return $store;
    }

    public function getProvince()
    {
        return $this->hasOne(Area::className(), ['id' => 'province_id']);
    }

    public function getCity()
    {
        return $this->hasOne(Area::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'area_id']);
    }

    //同商城类别表建立关系
    public function getTaxonomy()
    {
        return $this->hasOne(StoreTaxonomy::className(), ['id' => 'store_taxonomy']);
    }
}
