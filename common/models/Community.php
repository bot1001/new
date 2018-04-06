<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "community_basic".
 *
 * @property integer $community_id
 * @property string $community_name
 * @property string $community_logo
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $community_address
 * @property string $community_longitude
 * @property string $community_latitude
 *
 * @property UserInvoice[] $userInvoices
 */
class Community extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_basic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_name'], 'required'],
            [['province_id', 'city_id', 'area_id'], 'integer'],
            [['community_longitude', 'community_latitude'], 'number'],
            [['community_name', 'community_address'], 'string', 'max' => 64],
            [['community_logo'], 'string', 'max' => 300],
            [['community_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'community_id' => '编号',
            'community_name' => '小区',
            'community_logo' => 'Logo',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '地区',
            'community_address' => '地址',
            'community_longitude' => '经度',
            'community_latitude' => '纬度',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInvoices()
    {
        return $this->hasMany(UserInvoice::className(), ['community_id' => 'community_id']);
    }
}
