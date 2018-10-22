<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_basic".
 *
 * @property int $store_id （商店ID）
 * @property string $store_name （商店名称）
 * @property string $store_phone （商店电话）
 * @property string $store_cover （商店缩略图）
 * @property int $province_id （所在省份）
 * @property int $city_id （所在城市）
 * @property int $area_id （所在区/县）
 * @property string $person （法人或者负责人）
 * @property string $store_address （商店地址）
 * @property string $store_introduce （商店介绍）
 * @property double $store_code （公司代码）
 * @property double $store_people （公司人数）
 * @property int $add_time （录入时间）
 * @property int $is_certificate （是否认证，默认0-未认证）
 * @property int $store_sort （排序，默认0，预留字段）
 * @property string $store_status （状态,1启用,0禁用，2待审核，3锁定）
 * @property int $type 类型,1,超市,2,商店
 * @property int $store_taxonomy （行业）
 *
 * @property ProductBasic[] $productBasics
 * @property StoreAccount[] $storeAccounts
 * @property Area $province
 * @property Area $city
 * @property Area $area
 * @property StoreTaxonomy $storeTaxonomy
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
          [['store_name', 'store_phone', 'province_id', 'city_id', 'area_id', 'store_address', 'store_status', 'type'], 'required'],
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
            [['province_id', 'city_id', 'area_id', 'add_time', 'is_certificate', 'store_sort', 'type', 'store_taxonomy', 'store_people'], 'integer'],
            [['store_name', 'store_phone', 'store_address', 'store_status'], 'string', 'max' => 64],
            [['store_cover', 'store_code'], 'string', 'max' => 300],
            [['person'], 'string', 'max' => 128],
            [['store_introduce'], 'string', 'max' => 20000],
            [['area_id', 'store_name'], 'unique', 'targetAttribute' => ['area_id', 'store_name']],
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
            'store_id' => 'Store ID',
            'store_name' => '店名',
            'store_phone' => '联系方式',
            'store_cover' => '缩略图',
            'province_id' => '省份',
            'city_id' => '城市',
            'area_id' => '地区',
            'person' => '负责人',
            'store_address' => '地址',
            'store_introduce' => '店铺介绍',
            'store_code' => '公司代码',
            'store_people' => '公司人数',
            'add_time' => '创建时间',
            'is_certificate' => '认证',
            'store_sort' => '位置',
            'store_status' => '状态',
            'type' => '规模',
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
        return $this->hasOne(Product::className(), ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //获取商店
    static function getStore()
    {
        $store = self::find()
            ->select('store_name, store_id')
            ->indexBy('store_id')
            ->column();

        return $store;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Area::className(), ['id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Area::className(), ['id' => 'city_id']);
    }

    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'area_id']);
    }

    //同商城类别表建立关系
    public function getTaxonomy()
    {
        return $this->hasOne(StoreTaxonomy::className(), ['id' => 'store_taxonomy']);
    }

    public function getAccount()
    {
        return $this->hasOne(StoreAccount::className(), ['store_id' => 'store_id']);
    }
}
