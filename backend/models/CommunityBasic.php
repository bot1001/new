<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_basic".
 *
 * @property int $community_id （小区ID）
 * @property int $company （公司）
 * @property string $community_name （小区名称）
 * @property string $community_logo （小区LOGO）
 * @property int $province_id （所在省份）
 * @property int $city_id （所在城市）
 * @property int $area_id （所在区/县）
 * @property string $community_address (小区地址)
 * @property string $community_longitude （经度）
 * @property string $community_latitude （纬度）
 *
 * @property Company $company0
 * @property CostRelation[] $costRelations
 * @property Information[] $informations
 * @property UserInvoice[] $userInvoices
 * @property WaterMeter[] $waterMeters
 */
class CommunityBasic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_basic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company', 'community_name'], 'required'],
            [['company', 'province_id', 'city_id', 'area_id'], 'integer'],
            [['community_longitude', 'community_latitude'], 'number'],
            [['community_name', 'community_address'], 'string', 'max' => 64],
            [['community_logo'], 'string', 'max' => 300],
            [['company', 'community_name'], 'unique', 'targetAttribute' => ['company', 'community_name']],
            [['company'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'community_id' => '序号',
            'company' => '公司',
            'community_name' => '名称',
            'community_logo' => 'LOGO',
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
    public function getC()
    {
        return $this->hasOne(Company::className(), ['id' => 'company']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostRelations()
    {
        return $this->hasMany(CostRelation::className(), ['community' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInformations()
    {
        return $this->hasMany(Information::className(), ['community' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInvoices()
    {
        return $this->hasMany(UserInvoice::className(), ['community_id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaterMeters()
    {
        return $this->hasMany(WaterMeter::className(), ['community' => 'community_id']);
    }
	
	//获取关联小区
	public function getR()
    {
        return $this->hasOne(CommunityRealestate::className(), ['community_id' => 'community_id']);
    }
}
